<?php

use App\Models\Category;
use App\Models\Quiz;
use App\Services\BattleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;

uses(RefreshDatabase::class);

beforeEach(function () {
    Redis::flushDB();
});

afterEach(function () {
    Redis::flushDB();
});

it('can cleanup expired battles', function () {
    $battleService = app(BattleService::class);

    // Create test data
    $category = Category::factory()->create();
    $quiz = Quiz::factory()->create(['category_id' => $category->id]);

    // Create some test battles
    $battleId1 = $battleService->createBattle($quiz, ['player1', 'player2']);
    $battleId2 = $battleService->createBattle($quiz, ['player3', 'player4']);

    // Manually set one battle as finished and old
    $battleData = $battleService->getBattleData($battleId1);
    $battleData['status'] = 'finished';
    $battleData['finished_at'] = now()->subHours(2)->timestamp; // 2 hours ago
    $battleService->setBattleData($battleId1, $battleData);

    // Manually set another battle as expired (created more than 5 minutes ago)
    $battleData2 = $battleService->getBattleData($battleId2);
    $battleData2['created_at'] = now()->subMinutes(10)->timestamp; // 10 minutes ago, older than 5 min expiry
    $battleService->setBattleData($battleId2, $battleData2);

    // Verify battles exist before cleanup
    expect($battleService->getBattleData($battleId1))->not->toBeNull();
    expect($battleService->getBattleData($battleId2))->not->toBeNull();

    // Run cleanup
    $cleanedCount = $battleService->cleanupAllBattles();

    // Verify cleanup results
    expect($cleanedCount)->toBe(2);
    expect($battleService->getBattleData($battleId1))->toBeNull();
    expect($battleService->getBattleData($battleId2))->toBeNull();
});
it('does not cleanup active battles', function () {
    $battleService = app(BattleService::class);

    // Create test data
    $category = Category::factory()->create();
    $quiz = Quiz::factory()->create(['category_id' => $category->id]);

    // Create a fresh battle
    $battleId = $battleService->createBattle($quiz, ['player1', 'player2']);

    // Verify battle exists before cleanup
    expect($battleService->getBattleData($battleId))->not->toBeNull();

    // Run cleanup
    $cleanedCount = $battleService->cleanupAllBattles();

    // Verify active battle is not cleaned up
    expect($cleanedCount)->toBe(0);
    expect($battleService->getBattleData($battleId))->not->toBeNull();
});

it('can cleanup invalid battle data', function () {
    $battleService = app(BattleService::class);

    // Add invalid data to Redis
    Redis::set('battle:invalid-battle', 'invalid-json-data');

    // Run cleanup
    $cleanedCount = $battleService->cleanupAllBattles();

    // Verify invalid data is cleaned up
    expect($cleanedCount)->toBe(1);
    expect(Redis::get('battle:invalid-battle'))->toBeNull();
});

it('can cleanup ongoing battles that exceeded end time with grace period', function () {
    $battleService = app(BattleService::class);

    // Create test data
    $category = Category::factory()->create();
    $quiz = Quiz::factory()->create(['category_id' => $category->id]);

    // Create a battle
    $battleId = $battleService->createBattle($quiz, ['player1', 'player2']);

    // Set battle as ongoing but past end time + grace period (5 minutes grace period default)
    $battleData = $battleService->getBattleData($battleId);
    $battleData['status'] = 'ongoing';
    $battleData['ends_at'] = now()->subMinutes(10)->timestamp; // Ended 10 minutes ago (past grace period)
    $battleService->setBattleData($battleId, $battleData);

    // Run cleanup
    $cleanedCount = $battleService->cleanupAllBattles();

    // Verify battle is cleaned up
    expect($cleanedCount)->toBe(1);
    expect($battleService->getBattleData($battleId))->toBeNull();
});
it('runs cleanup command successfully', function () {
    $this->artisan('battle:cleanup')
        ->expectsOutput('Starting battle cleanup...')
        ->expectsOutputToContain('Cleanup completed. Removed')
        ->assertSuccessful();
});
