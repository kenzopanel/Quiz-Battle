<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Player channels for matchmaking notifications - using public channels
Broadcast::channel('player.{sessionToken}', function ($user, $sessionToken) {
    // For guest users, always allow access
    return ['id' => $sessionToken, 'name' => 'Player'];
});

// Battle channels for real-time battle events  
Broadcast::channel('battle.{battleId}', function ($user, $battleId) {
    // Allow access to anyone - battles are identified by session tokens, not users
    return ['id' => $battleId, 'name' => 'Battle Player'];
});

// Matchmaking presence channels (optional - for showing number of waiting players)
Broadcast::channel('matchmaking.{categoryId}', function ($user, $categoryId) {
    return ['id' => uniqid(), 'name' => 'Waiting Player'];
});
