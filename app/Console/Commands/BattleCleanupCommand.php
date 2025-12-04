<?php

namespace App\Console\Commands;

use App\Services\BattleService;
use Illuminate\Console\Command;

class BattleCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'battle:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired and finished battles from Redis';

    /**
     * Execute the console command.
     */
    public function handle(BattleService $battleService): int
    {
        $this->info('Starting battle cleanup...');

        $cleanedCount = $battleService->cleanupAllBattles();

        $this->info("Cleanup completed. Removed {$cleanedCount} expired battles.");

        return Command::SUCCESS;
    }
}
