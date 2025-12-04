<?php

use App\Models\Category;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Room;
use App\Services\BattleService;
use App\Services\MatchmakingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTestData(): array
{
    $category = Category::factory()->create(['name' => 'Test Category']);
    $quiz = Quiz::factory()->for($category)->create();
    $questions = Question::factory()->count(3)->for($quiz)->create();

    foreach ($questions as $question) {
        // Create 3 incorrect options and 1 correct option
        QuestionOption::factory()->count(3)->for($question)->create(['is_correct' => false]);
        QuestionOption::factory()->for($question)->correct()->create();
    }

    return compact('category', 'quiz', 'questions');
}

test('index page shows categories', function () {
    $testData = createTestData();

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee($testData['category']->name)
        ->assertSee('Quiz Battle Arena');
});

test('join page displays correctly', function () {
    $response = $this->get('/join');

    $response->assertStatus(200)
        ->assertSee('Join with Code')
        ->assertSee('Room Code');
});

test('can start matchmaking with valid category', function () {
    $testData = createTestData();

    $response = $this->post('/matchmaking/start', [
        'category_id' => $testData['category']->id,
    ]);

    $response->assertStatus(200)
        ->assertSee('Finding Opponent')
        ->assertSee($testData['category']->name);

    // Check session has required data
    expect(session('session_token'))->not->toBeNull()
        ->and(session('category_id'))->toBe($testData['category']->id);
});

test('matchmaking requires valid category', function () {
    $response = $this->post('/matchmaking/start', [
        'category_id' => 999,
    ]);

    $response->assertStatus(302)
        ->assertSessionHasErrors('category_id');
});

test('can cancel matchmaking', function () {
    $testData = createTestData();

    // Start matchmaking first
    $this->post('/matchmaking/start', [
        'category_id' => $testData['category']->id,
    ]);

    $response = $this->post('/matchmaking/cancel');

    $response->assertRedirect('/');

    expect(session('session_token'))->toBeNull()
        ->and(session('category_id'))->toBeNull();
});

test('can create room with valid data', function () {
    $testData = createTestData();

    $response = $this->post('/rooms', [
        'name' => 'Test Room',
        'category_id' => $testData['category']->id,
        'max_players' => 2,
    ]);

    $response->assertRedirect()
        ->assertSessionHas('session_token');
});

test('joining room with invalid code shows error', function () {
    $response = $this->post('/rooms/join', [
        'code' => 'INVALID1',
    ]);

    $response->assertStatus(302)
        ->assertSessionHasErrors('code');
});

test('battle service can create battle', function () {
    $testData = createTestData();
    $battleService = app(BattleService::class);
    $players = ['player1', 'player2'];

    $battleId = $battleService->createBattle($testData['quiz'], $players);

    expect($battleId)->not->toBeNull();

    $battleData = $battleService->getBattleData($battleId);
    expect($battleData)->not->toBeNull()
        ->and($battleData['quiz_id'])->toBe($testData['quiz']->id)
        ->and($battleData['players'])->toBe($players)
        ->and($battleData['status'])->toBe('waiting');
});

test('matchmaking service can add player to queue', function () {
    $testData = createTestData();
    $matchmakingService = app(MatchmakingService::class);

    $sessionToken = $matchmakingService->joinQueue($testData['category']->id);

    expect($sessionToken)->not->toBeNull()
        ->and($sessionToken)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

test('room model generates unique codes', function () {
    $testData = createTestData();

    $room1 = Room::factory()->for($testData['category'])->create();
    $room2 = Room::factory()->for($testData['category'])->create();

    expect($room1->code)->not->toBe($room2->code)
        ->and(strlen($room1->code))->toBe(8)
        ->and(strlen($room2->code))->toBe(8);
});

test('questions have correct relationships', function () {
    $testData = createTestData();
    $question = $testData['questions']->first();

    expect($question->quiz)->not->toBeNull()
        ->and($question->quiz->id)->toBe($testData['quiz']->id)
        ->and($question->options)->toHaveCount(4)
        ->and($question->options->where('is_correct', true))->toHaveCount(1)
        ->and($question->options->where('is_correct', false))->toHaveCount(3);
});

test('category has quizzes relationship', function () {
    $testData = createTestData();

    expect($testData['category']->quizzes)->toHaveCount(1)
        ->and($testData['category']->quizzes->first()->id)->toBe($testData['quiz']->id);
});
