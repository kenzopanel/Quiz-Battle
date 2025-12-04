<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Anti-Cheat Settings
            [
                'key' => 'QUIZ_ANTI_CHEAT_TAB_SWITCH',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti-Cheat Settings',
                'label' => 'Auto-lose on tab switch',
                'description' => 'Automatically lose the game when switching tabs'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_PAGE_VISIBILITY',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti-Cheat Settings',
                'label' => 'Auto-lose on page hide',
                'description' => 'Automatically lose when page becomes hidden'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_BEFOREUNLOAD',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti-Cheat Settings',
                'label' => 'Show exit confirmation',
                'description' => 'Show warning when user tries to close/refresh page'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_UNLOAD',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti-Cheat Settings',
                'label' => 'Auto-lose on page unload',
                'description' => 'Automatically lose when page is being unloaded'
            ],

            // Battle Settings
            [
                'key' => 'QUIZ_DEFAULT_TIMEOUT',
                'value' => '60',
                'type' => 'number',
                'group' => 'Battle Settings',
                'label' => 'Default quiz timeout (seconds)',
                'description' => 'Default timeout for entire quiz'
            ],
            [
                'key' => 'QUIZ_DEFAULT_PER_QUESTION_TIME',
                'value' => '15',
                'type' => 'number',
                'group' => 'Battle Settings',
                'label' => 'Default time per question (seconds)',
                'description' => 'Default time allowed per question'
            ],
            [
                'key' => 'QUIZ_MATCHMAKING_TIMEOUT',
                'value' => '30',
                'type' => 'number',
                'group' => 'Battle Settings',
                'label' => 'Matchmaking timeout (seconds)',
                'description' => 'How long to wait for opponent during matchmaking'
            ],
            [
                'key' => 'QUIZ_BATTLE_GRACE_PERIOD',
                'value' => '5',
                'type' => 'number',
                'group' => 'Battle Settings',
                'label' => 'Battle grace period (seconds)',
                'description' => 'Grace period before battle starts'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
