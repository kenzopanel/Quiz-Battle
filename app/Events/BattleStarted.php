<?php

namespace App\Events;

use App\Models\Quiz;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BattleStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $battleId,
        public Quiz $quiz,
        public int $startedAt,
        public int $endsAt
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('battle.' . $this->battleId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'battle.started';
    }

    public function broadcastWith(): array
    {
        return [
            'battle_id' => $this->battleId,
            'quiz' => [
                'id' => $this->quiz->id,
                'title' => $this->quiz->title,
                'questions' => $this->quiz->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'options' => $question->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'is_correct' => $option->is_correct, // Client will validate
                            ];
                        }),
                    ];
                }),
                'per_question_time' => $this->quiz->per_question_time,
                'timeout_seconds' => $this->quiz->timeout_seconds,
            ],
            'started_at' => $this->startedAt,
            'ends_at' => $this->endsAt,
        ];
    }
}
