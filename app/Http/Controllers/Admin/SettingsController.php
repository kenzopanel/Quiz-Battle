<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $this->initializeDefaultSettings();
            $settingsData = Setting::all()->groupBy('group')->sortKeys();

            $settings = [];
            foreach ($settingsData as $groupName => $groupSettings) {
                if (empty($groupName)) {
                    $groupName = 'Pengaturan Dasar';
                }

                $settings[$groupName] = [];
                foreach ($groupSettings as $setting) {
                    $settings[$groupName][$setting->key] = [
                        'label' => $setting->label ?? $setting->key,
                        'type' => $setting->type ?? 'string',
                        'value' => $setting->typed_value,
                        'description' => $setting->description
                    ];
                }
            }

            return view('admin.settings.index', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Failed to load settings', ['error' => $e->getMessage()]);
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load settings. Please try again.');
        }
    }

    private function initializeDefaultSettings()
    {
        $defaultSettings = [
            [
                'key' => 'QUIZ_ANTI_CHEAT_TAB_SWITCH',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti Kecurangan',
                'label' => 'Kalah jika pindah tab',
                'description' => 'User akan otomatis kalah jika berpindah tab'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_PAGE_VISIBILITY',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti Kecurangan',
                'label' => 'Kalah jika halaman disembunyikan',
                'description' => 'User akan otomatis kalah jika halaman disembunyikan'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_BEFOREUNLOAD',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti Kecurangan',
                'label' => 'Konfirmasi sebelum keluar halaman',
                'description' => 'User akan mendapatkan konfirmasi sebelum keluar atau refresh halaman'
            ],
            [
                'key' => 'QUIZ_ANTI_CHEAT_UNLOAD',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Anti Kecurangan',
                'label' => 'Kalah jika halaman ditutup',
                'description' => 'User akan otomatis kalah jika halaman ditutup'
            ],

            [
                'key' => 'QUIZ_DEFAULT_TIMEOUT',
                'value' => '60',
                'type' => 'number',
                'group' => 'Pengaturan Pertarungan',
                'label' => 'Batas waktu (detik)',
                'description' => 'Batas waktu default untuk seluruh kuis'
            ],
            [
                'key' => 'QUIZ_DEFAULT_PER_QUESTION_TIME',
                'value' => '15',
                'type' => 'number',
                'group' => 'Pengaturan Pertarungan',
                'label' => 'Batas waktu per soal (detik)',
                'description' => 'Batas waktu default untuk setiap soal'
            ],
            [
                'key' => 'QUIZ_MATCHMAKING_TIMEOUT',
                'value' => '30',
                'type' => 'number',
                'group' => 'Pengaturan Pertarungan',
                'label' => 'Batas waktu mencari lawan (detik)',
                'description' => 'Berapa lama mencari lawan saat matchmaking'
            ],
            [
                'key' => 'QUIZ_BATTLE_GRACE_PERIOD',
                'value' => '5',
                'type' => 'number',
                'group' => 'Pengaturan Pertarungan',
                'label' => 'Battle grace period (detik)',
                'description' => 'Waktu tunda sebelum pertarungan dimulai'
            ],
        ];

        foreach ($defaultSettings as $settingData) {
            Setting::firstOrCreate(
                ['key' => $settingData['key']],
                $settingData
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $updatedCount = 0;

            $request->validate([
                '*' => 'nullable|string|max:1000'
            ]);

            foreach ($request->all() as $key => $value) {
                if (in_array($key, ['_token', '_method'], true)) continue;

                $setting = Setting::where('key', $key)->first();
                if (!$setting) {
                    Log::warning('Attempt to update non-existent setting: ' . $key);
                    continue;
                }

                if ($setting->type === 'boolean') {
                    $value = $value === 'on' ? 'true' : 'false';
                }

                if ($setting->type === 'number' && !is_numeric($value) && $value !== '') {
                    return redirect()->route('admin.settings.index')
                        ->with('error', "Invalid number value for {$setting->label}");
                }

                $setting->setTypedValue($value);
                $setting->save();
                $updatedCount++;
            }

            $booleanSettings = Setting::where('type', 'boolean')->get();
            foreach ($booleanSettings as $setting) {
                if (!$request->has($setting->key)) {
                    $setting->setTypedValue(false);
                    $setting->save();
                    $updatedCount++;
                }
            }

            return redirect()->route('admin.settings.index')
                ->with('success', "Settings updated successfully! ({$updatedCount} settings modified)");
        } catch (\Exception $e) {
            Log::error('Failed to update settings', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return redirect()->route('admin.settings.index')
                ->with('error', 'Failed to update settings. Please try again.');
        }
    }
}
