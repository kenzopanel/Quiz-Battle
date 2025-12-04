<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class CleanupExpiredRooms extends Command
{
    protected $signature = 'rooms:cleanup';
    protected $description = 'Clean up expired rooms';

    public function handle(): void
    {
        $expiredRooms = Room::where('expires_at', '<', now())
            ->orWhere(function ($query) {
                $query->where('status', 'waiting')
                    ->where('created_at', '<', now()->subHours(2));
            })
            ->get();

        $count = $expiredRooms->count();

        foreach ($expiredRooms as $room) {
            $this->info("Deleting expired room: {$room->name} ({$room->code})");
            $room->delete();
        }

        $this->info("Cleanup completed. Deleted {$count} expired rooms.");
    }
}
