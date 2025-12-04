<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchFound implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $battleId,
        public array $players
    ) {}

    public function broadcastOn(): array
    {
        $channels = [];

        foreach ($this->players as $player) {
            $channels[] = new Channel('player.' . $player);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'match.found';
    }

    public function broadcastWith(): array
    {
        return [
            'battle_id' => $this->battleId,
            'message' => 'Match found! Redirecting to battle...',
        ];
    }
}
