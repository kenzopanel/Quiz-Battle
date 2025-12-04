<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Battle Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for quiz battles and matchmaking system.
    |
    */

    'battle' => [
        /*
        |--------------------------------------------------------------------------
        | Battle Expiry Time
        |--------------------------------------------------------------------------
        |
        | The time in seconds after which battle data expires in Redis.
        | Default: 300 seconds (5 minutes)
        |
        */
        'battle_expiry' => env('QUIZ_BATTLE_EXPIRY', 300),

        /*
        |--------------------------------------------------------------------------
        | Cleanup Schedule
        |--------------------------------------------------------------------------
        |
        | How often the battle cleanup command should run.
        | This is used for automatic cleanup scheduling.
        |
        */
        'cleanup_interval' => env('QUIZ_CLEANUP_INTERVAL', 'hourly'),

        /*
        |--------------------------------------------------------------------------
        | Finished Battle Retention
        |--------------------------------------------------------------------------
        |
        | How long to keep finished battles in Redis before cleanup (in seconds).
        | Default: 3600 seconds (1 hour)
        |
        */
        'finished_battle_retention' => env('QUIZ_FINISHED_BATTLE_RETENTION', 3600),

        /*
        |--------------------------------------------------------------------------
        | Grace Period for Ongoing Battles
        |--------------------------------------------------------------------------
        |
        | Extra time to keep ongoing battles after their end time (in seconds).
        | Default: 300 seconds (5 minutes)
        |
        */
        'ongoing_battle_grace_period' => env('QUIZ_ONGOING_BATTLE_GRACE_PERIOD', 300),
    ],

    'matchmaking' => [
        /*
        |--------------------------------------------------------------------------
        | Matchmaking Timeout
        |--------------------------------------------------------------------------
        |
        | Maximum time to wait for an opponent in seconds.
        | Default: 30 seconds
        |
        */
        'timeout' => env('QUIZ_MATCHMAKING_TIMEOUT', 30),

        /*
        |--------------------------------------------------------------------------
        | Player Expiry Time
        |--------------------------------------------------------------------------
        |
        | Time after which a player is removed from matchmaking queue.
        | Default: 60 seconds
        |
        */
        'player_expiry' => env('QUIZ_MATCHMAKING_PLAYER_EXPIRY', 60),
    ],
];
