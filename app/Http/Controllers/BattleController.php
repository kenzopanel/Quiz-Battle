<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Services\BattleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BattleController extends Controller
{
    public function __construct(
        private BattleService $battleService
    ) {}

    public function show(string $battleId)
    {
        $battleData = $this->battleService->getBattleData($battleId);

        if (!$battleData) {
            return redirect()->route('index')->with('error', 'Battle not found');
        }

        $quiz = Quiz::with(['questions.options'])->find($battleData['quiz_id']);

        if (!$quiz) {
            return redirect()->route('index')->with('error', 'Quiz not found');
        }

        $sessionToken = Session::get('session_token');

        if (!$sessionToken || !in_array($sessionToken, $battleData['players'])) {
            return redirect()->route('index')->with('error', 'You are not part of this battle');
        }

        return view('battle', compact('battleId', 'quiz', 'sessionToken', 'battleData'));
    }

    public function joinBattle(Request $request, string $battleId)
    {
        $sessionToken = Session::get('session_token');

        if (!$sessionToken) {
            return response()->json(['error' => 'No session token'], 400);
        }

        $joined = $this->battleService->joinBattle($battleId, $sessionToken);

        if (!$joined) {
            return response()->json(['error' => 'Cannot join battle'], 400);
        }

        return response()->json(['success' => true]);
    }

    public function submitScore(Request $request, string $battleId)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'total_time_ms' => 'required|integer|min:0',
        ]);

        $sessionToken = Session::get('session_token');

        if (!$sessionToken) {
            return response()->json(['error' => 'No session token'], 400);
        }

        $submitted = $this->battleService->submitPlayerScore(
            $battleId,
            $sessionToken,
            $request->score,
            $request->total_time_ms
        );

        if (!$submitted) {
            return response()->json(['error' => 'Cannot submit score'], 400);
        }

        return response()->json(['success' => true]);
    }

    public function autoLose(Request $request, string $battleId)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $sessionToken = Session::get('session_token');

        if (!$sessionToken) {
            return response()->json(['error' => 'No session token'], 400);
        }

        $this->battleService->autoLose($battleId, $sessionToken, $request->reason);

        return response()->json(['success' => true]);
    }
}
