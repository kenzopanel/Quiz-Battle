<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BattleEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $battleId,
        public ?string $winner,
        public array $playerScores
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('battle.' . $this->battleId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'battle.ended';
    }

    public function broadcastWith(): array
    {
        return [
            'battle_id' => $this->battleId,
            'winner' => $this->winner,
            'scores' => $this->playerScores,
            'message' => $this->winner ? 'Battle finished!' : 'Battle ended in a draw!',
        ];
    }
}
