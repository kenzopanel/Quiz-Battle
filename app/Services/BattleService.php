<?php

namespace App\Services;

use App\Events\BattleEnded;
use App\Events\BattleStarted;
use App\Events\PlayerJoined;
use App\Models\Quiz;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class BattleService
{
    private const BATTLE_KEY_PREFIX = 'battle:';

    private function getBattleExpiry(): int
    {
        return config('quiz.battle.battle_expiry', 300);
    }

    public function createBattle(Quiz $quiz, array $playerTokens): string
    {
        $battleId = Str::uuid()->toString();

        $battleData = [
            'id' => $battleId,
            'quiz_id' => $quiz->id,
            'status' => 'waiting',
            'players' => $playerTokens,
            'player_scores' => [],
            'player_times' => [],
            'started_at' => null,
            'ends_at' => null,
            'created_at' => now()->timestamp,
        ];

        $this->setBattleData($battleId, $battleData);

        return $battleId;
    }

    public function joinBattle(string $battleId, string $sessionToken): bool
    {
        $battleData = $this->getBattleData($battleId);

        if (!$battleData || !in_array($sessionToken, $battleData['players'])) {
            return false;
        }

        if (!isset($battleData['joined_players'])) {
            $battleData['joined_players'] = [];
        }

        if (!in_array($sessionToken, $battleData['joined_players'])) {
            $battleData['joined_players'][] = $sessionToken;
            $this->setBattleData($battleId, $battleData);

            broadcast(new PlayerJoined($battleId, $sessionToken));

            if (count($battleData['joined_players']) === count($battleData['players'])) {
                $this->startBattle($battleId);
            }
        }

        return true;
    }

    public function startBattle(string $battleId): bool
    {
        $battleData = $this->getBattleData($battleId);

        if (!$battleData || $battleData['status'] !== 'waiting') {
            return false;
        }

        $quiz = Quiz::with(['questions.options'])->find($battleData['quiz_id']);

        if (!$quiz) {
            return false;
        }

        $battleData['status'] = 'ongoing';
        $battleData['started_at'] = now()->timestamp;
        $battleData['ends_at'] = now()->addSeconds($quiz->timeout_seconds)->timestamp;

        $this->setBattleData($battleId, $battleData);

        broadcast(new BattleStarted($battleId, $quiz, now()->timestamp, $battleData['ends_at']));

        return true;
    }

    public function submitPlayerScore(string $battleId, string $sessionToken, int $score, int $totalTimeMs): bool
    {
        $battleData = $this->getBattleData($battleId);

        if (!$battleData || !in_array($sessionToken, $battleData['players'])) {
            return false;
        }

        $battleData['player_scores'][$sessionToken] = $score;
        $battleData['player_times'][$sessionToken] = $totalTimeMs;

        $this->setBattleData($battleId, $battleData);

        if (count($battleData['player_scores']) === count($battleData['players'])) {
            $this->endBattle($battleId);
        }

        return true;
    }

    public function autoLose(string $battleId, string $sessionToken, string $reason = 'unknown'): bool
    {
        $battleData = $this->getBattleData($battleId);

        if (!$battleData || !in_array($sessionToken, $battleData['players'])) {
            return false;
        }

        $battleData['player_scores'][$sessionToken] = 0;
        $battleData['player_times'][$sessionToken] = 999999; // Max time
        $battleData['auto_lose'][$sessionToken] = $reason;

        $this->setBattleData($battleId, $battleData);

        $this->endBattle($battleId);

        return true;
    }

    public function endBattle(string $battleId): bool
    {
        $battleData = $this->getBattleData($battleId);

        if (!$battleData) {
            return false;
        }

        $battleData['status'] = 'finished';
        $battleData['finished_at'] = now()->timestamp;

        $winner = $this->determineWinner($battleData);
        $battleData['winner'] = $winner;

        $this->setBattleData($battleId, $battleData);

        broadcast(new BattleEnded($battleId, $winner, $battleData['player_scores']));

        return true;
    }

    public function getBattleData(string $battleId): ?array
    {
        $data = Redis::get($this->getBattleKey($battleId));
        return $data ? json_decode($data, true) : null;
    }

    public function setBattleData(string $battleId, array $data): void
    {
        Redis::setex(
            $this->getBattleKey($battleId),
            $this->getBattleExpiry(),
            json_encode($data)
        );
    }

    private function determineWinner(array $battleData): ?string
    {
        if (empty($battleData['player_scores']) || count($battleData['player_scores']) < 2) {
            return null;
        }

        $scores = $battleData['player_scores'];
        $times = $battleData['player_times'] ?? [];

        $players = array_keys($scores);
        $player1 = $players[0];
        $player2 = $players[1];

        $score1 = $scores[$player1];
        $score2 = $scores[$player2];

        if ($score1 !== $score2) {
            return $score1 > $score2 ? $player1 : $player2;
        }

        if ($score1 === 0 && $score2 === 0) {
            return null; // Draw
        }

        $time1 = $times[$player1] ?? 999999;
        $time2 = $times[$player2] ?? 999999;

        return $time1 < $time2 ? $player1 : $player2;
    }

    public function cleanupAllBattles(): int
    {
        $pattern = self::BATTLE_KEY_PREFIX . '*';
        $keys = Redis::keys($pattern);
        $cleanedCount = 0;
        $currentTime = now()->timestamp;

        $finishedRetention = config('quiz.battle.finished_battle_retention', 3600);
        $gracePeriod = config('quiz.battle.ongoing_battle_grace_period', 300);

        foreach ($keys as $key) {
            $actualKey = str_replace(config('database.redis.options.prefix', ''), '', $key);

            $battleData = json_decode(Redis::get($actualKey), true);

            if (!$battleData) {
                Redis::del($actualKey);
                $cleanedCount++;
                continue;
            }

            if (
                $battleData['status'] === 'finished' &&
                isset($battleData['finished_at']) &&
                ($currentTime - $battleData['finished_at']) > $finishedRetention
            ) {
                Redis::del($actualKey);
                $cleanedCount++;
                continue;
            }

            if (
                isset($battleData['created_at']) &&
                ($currentTime - $battleData['created_at']) > $this->getBattleExpiry()
            ) {
                Redis::del($actualKey);
                $cleanedCount++;
                continue;
            }

            if (
                $battleData['status'] === 'ongoing' &&
                isset($battleData['ends_at']) &&
                ($currentTime > $battleData['ends_at'] + $gracePeriod)
            ) {
                Redis::del($actualKey);
                $cleanedCount++;
            }
        }

        return $cleanedCount;
    }

    private function getBattleKey(string $battleId): string
    {
        return self::BATTLE_KEY_PREFIX . $battleId;
    }
}
