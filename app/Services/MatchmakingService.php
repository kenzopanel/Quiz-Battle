<?php

namespace App\Services;

use App\Events\MatchFound;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class MatchmakingService
{
    private const MATCHMAKING_KEY_PREFIX = 'matchmaking:';

    private function getMatchmakingTimeout(): int
    {
        return config('quiz.battle.matchmaking_timeout', 30);
    }

    public function joinQueue(int $categoryId): string
    {
        $sessionToken = Str::uuid()->toString();
        $queueKey = $this->getQueueKey($categoryId);

        $playerData = [
            'session_token' => $sessionToken,
            'joined_at' => now()->timestamp,
            'category_id' => $categoryId,
        ];

        Redis::lpush($queueKey, json_encode($playerData));
        $this->tryMatch($categoryId);

        return $sessionToken;
    }

    public function leaveQueue(int $categoryId, string $sessionToken): void
    {
        $queueKey = $this->getQueueKey($categoryId);
        $queueItems = Redis::lrange($queueKey, 0, -1);

        foreach ($queueItems as $index => $item) {
            $playerData = json_decode($item, true);
            if ($playerData['session_token'] === $sessionToken) {
                Redis::lrem($queueKey, 1, $item);
                break;
            }
        }
    }

    public function tryMatch(int $categoryId): ?array
    {
        $queueKey = $this->getQueueKey($categoryId);
        $queueItems = Redis::lrange($queueKey, 0, -1);

        if (count($queueItems) >= 2) {
            $player1Data = json_decode(Redis::lpop($queueKey), true);
            $player2Data = json_decode(Redis::lpop($queueKey), true);

            if (!$player1Data || !$player2Data) {
                return null;
            }

            $quiz = Quiz::where('category_id', $categoryId)->inRandomOrder()->first();

            if (!$quiz) {
                Redis::lpush($queueKey, json_encode($player1Data));
                Redis::lpush($queueKey, json_encode($player2Data));
                return null;
            }

            $battleService = app(BattleService::class);
            $battleId = $battleService->createBattle(
                $quiz,
                [$player1Data['session_token'], $player2Data['session_token']]
            );

            broadcast(new MatchFound(
                $battleId,
                [$player1Data['session_token'], $player2Data['session_token']]
            ));

            return [
                'battle_id' => $battleId,
                'players' => [$player1Data['session_token'], $player2Data['session_token']],
            ];
        }

        return null;
    }

    public function cleanupExpiredPlayers(): int
    {
        $categories = Category::all();
        $removedCount = 0;

        foreach ($categories as $category) {
            $queueKey = $this->getQueueKey($category->id);
            $queueItems = Redis::lrange($queueKey, 0, -1);

            foreach ($queueItems as $item) {
                $playerData = json_decode($item, true);
                if (now()->timestamp - $playerData['joined_at'] > $this->getMatchmakingTimeout()) {
                    Redis::lrem($queueKey, 1, $item);
                    $removedCount++;
                }
            }
        }

        return $removedCount;
    }

    private function getQueueKey(int $categoryId): string
    {
        return self::MATCHMAKING_KEY_PREFIX . $categoryId;
    }
}
