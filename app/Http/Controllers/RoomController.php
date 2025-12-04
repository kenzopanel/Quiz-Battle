<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoined;
use App\Events\RoomStarted;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\Room;
use App\Services\BattleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function __construct(
        private BattleService $battleService
    ) {}

    public function index()
    {
        $rooms = Room::with('category')
            ->where('status', 'waiting')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('rooms.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'max_players' => 'required|integer|min:2|max:10',
        ]);

        $sessionToken = Session::get('session_token') ?? Str::uuid()->toString();
        Session::put('session_token', $sessionToken);

        $room = Room::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'creator_session_token' => $sessionToken,
            'max_players' => $request->max_players,
            'player_tokens' => [$sessionToken],
        ]);

        Session::put('room_id', $room->id);

        return redirect()->route('rooms.show', $room->code);
    }

    public function show(string $code)
    {
        $room = Room::with('category')->where('code', $code)->firstOrFail();

        if ($room->isExpired()) {
            return redirect()->route('rooms.index')->with('error', 'Room telah expired.');
        }

        $sessionToken = Session::get('session_token');
        $isCreator = $room->creator_session_token === $sessionToken;
        $isPlayer = in_array($sessionToken, $room->player_tokens ?? []);

        return view('rooms.show', compact('room', 'isCreator', 'isPlayer', 'sessionToken'));
    }

    public function join(Request $request)
    {
        return view('rooms.join');
    }

    public function joinRequest(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:8|exists:rooms,code',
        ]);

        $room = Room::where('code', $request->code)->first();

        if (!$room) {
            return back()->withErrors(['code' => 'Room tidak ditemukan']);
        }

        if ($room->isExpired()) {
            return back()->withErrors(['code' => 'Room telah expired']);
        }

        if ($room->status !== 'waiting') {
            return back()->withErrors(['code' => 'Room sudah dimulai atau selesai']);
        }

        $sessionToken = Session::get('session_token') ?? Str::uuid()->toString();
        Session::put('session_token', $sessionToken);

        if (!$room->canJoin($sessionToken)) {
            return back()->withErrors(['code' => 'Tidak bisa bergabung ke room ini']);
        }

        $room->addPlayer($sessionToken);
        Session::put('room_id', $room->id);

        // Broadcast player joined
        broadcast(new PlayerJoined($room->code, $sessionToken));

        return redirect()->route('rooms.show', $room->code);
    }

    public function leave(Request $request, string $code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        $sessionToken = Session::get('session_token');

        if (!$sessionToken || !in_array($sessionToken, $room->player_tokens ?? [])) {
            return redirect()->route('rooms.index');
        }

        $room->removePlayer($sessionToken);

        // If room becomes empty or creator leaves, delete room
        if (empty($room->player_tokens) || $room->creator_session_token === $sessionToken) {
            $room->delete();
        }

        Session::forget(['room_id']);

        return redirect()->route('rooms.index')->with('success', 'Berhasil keluar dari room');
    }

    public function start(Request $request, string $code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        $sessionToken = Session::get('session_token');

        // Only creator can start the room
        if ($room->creator_session_token !== $sessionToken) {
            return back()->withErrors(['error' => 'Hanya pembuat room yang bisa memulai']);
        }

        // Need at least 2 players
        if (count($room->player_tokens ?? []) < 2) {
            return back()->withErrors(['error' => 'Butuh minimal 2 pemain untuk memulai']);
        }

        // Get random quiz from the selected category
        $quiz = Quiz::where('category_id', $room->category_id)->inRandomOrder()->first();

        if (!$quiz) {
            return back()->withErrors(['error' => 'Tidak ada quiz untuk kategori ini']);
        }

        // Create battle
        $battleId = $this->battleService->createBattle($quiz, $room->player_tokens);

        // Update room status
        $room->update(['status' => 'ongoing']);

        // Broadcast room started to all players
        broadcast(new RoomStarted($room->code, $battleId));

        // Redirect all players to battle
        return redirect()->route('battle.show', $battleId);
    }
}
