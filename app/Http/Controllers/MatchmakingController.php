<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MatchmakingController extends Controller
{
    public function __construct(
        private MatchmakingService $matchmakingService
    ) {}

    public function start(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $categoryId = (int) $request->category_id;
        $category = Category::find($categoryId);
        $sessionToken = $this->matchmakingService->joinQueue($categoryId);

        Session::put('session_token', $sessionToken);
        Session::put('category_id', $categoryId);

        return view('matchmaking', compact('category', 'sessionToken'));
    }

    public function cancel(Request $request)
    {
        $sessionToken = Session::get('session_token');
        $categoryId = Session::get('category_id');

        if ($sessionToken && $categoryId) {
            $this->matchmakingService->leaveQueue($categoryId, $sessionToken);
        }

        Session::forget(['session_token', 'category_id']);

        return redirect()->route('index');
    }

    public function status(Request $request)
    {
        $sessionToken = Session::get('session_token');
        $categoryId = Session::get('category_id');

        if (!$sessionToken || !$categoryId) {
            return response()->json(['error' => 'No active matchmaking session'], 404);
        }

        return response()->json([
            'status' => 'waiting',
            'session_token' => $sessionToken,
            'category_id' => $categoryId,
        ]);
    }
}
