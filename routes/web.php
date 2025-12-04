<?php

use App\Http\Controllers\BattleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// Index routes
Route::get('/', [IndexController::class, 'index'])->name('index');

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

        // Quizzes
        Route::resource('quizzes', App\Http\Controllers\Admin\QuizController::class);

        // Questions (nested under quizzes)
        Route::resource('quizzes.questions', App\Http\Controllers\Admin\QuestionController::class);

        // Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    });
});

// Matchmaking routes
Route::post('/matchmaking/start', [MatchmakingController::class, 'start'])->name('matchmaking.start');
Route::post('/matchmaking/cancel', [MatchmakingController::class, 'cancel'])->name('matchmaking.cancel');
Route::get('/matchmaking/status', [MatchmakingController::class, 'status'])->name('matchmaking.status');

// Room routes
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
Route::get('/rooms/join', [RoomController::class, 'join'])->name('rooms.join');
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::get('/rooms/{code}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/rooms/join', [RoomController::class, 'joinRequest'])->name('rooms.join.request');
Route::post('/rooms/{code}/leave', [RoomController::class, 'leave'])->name('rooms.leave');
Route::post('/rooms/{code}/start', [RoomController::class, 'start'])->name('rooms.start');

// Battle routes
Route::get('/battle/{battleId}', [BattleController::class, 'show'])->name('battle.show');

// API routes for battle actions
Route::prefix('api/battle/{battleId}')->group(function () {
    Route::post('/join', [BattleController::class, 'joinBattle']);
    Route::post('/submit-score', [BattleController::class, 'submitScore']);
    Route::post('/auto-lose', [BattleController::class, 'autoLose']);
});
