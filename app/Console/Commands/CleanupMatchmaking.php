<?php

namespace App\Console\Commands;

use App\Services\MatchmakingService;
use Illuminate\Console\Command;

class CleanupMatchmaking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matchmaking:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired players from matchmaking queues';

    public function __construct(
        private MatchmakingService $matchmakingService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $removed = $this->matchmakingService->cleanupExpiredPlayers();

        $this->info("Removed {$removed} expired players from matchmaking queues.");

        return self::SUCCESS;
    }
}
