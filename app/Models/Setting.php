<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get typed value based on type
     */
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => in_array(strtolower($this->value), ['true', '1', 'on', 'yes'], true),
            'number' => is_numeric($this->value) ? (float) $this->value : 0,
            'json' => json_decode($this->value, true) ?? [],
            default => (string) $this->value,
        };
    }

    /**
     * Set value with proper type conversion
     */
    public function setTypedValue($value)
    {
        $this->value = match ($this->type) {
            'boolean' => $value && !in_array(strtolower((string)$value), ['false', '0', '', 'no', 'off'], true) ? 'true' : 'false',
            'number' => is_numeric($value) ? (string)$value : '0',
            'json' => is_array($value) ? json_encode($value) : (is_string($value) ? $value : json_encode($value)),
            default => (string) $value,
        };
        return $this;
    }

    /**
     * Get setting by key with default value
     */
    public static function getValue(string $key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->typed_value : $default;
        } catch (\Exception $e) {
            Log::warning('Failed to get setting: ' . $key, ['error' => $e->getMessage()]);
            return $default;
        }
    }

    /**
     * Set setting value
     */
    public static function setValue(string $key, $value, string $type = 'string', ?string $group = null, ?string $label = null, ?string $description = null)
    {
        try {
            $setting = static::updateOrCreate(
                ['key' => $key],
                [
                    'type' => $type,
                    'group' => $group,
                    'label' => $label,
                    'description' => $description
                ]
            );

            $setting->setTypedValue($value);
            $setting->save();

            return $setting;
        } catch (\Exception $e) {
            Log::error('Failed to set setting: ' . $key, ['error' => $e->getMessage(), 'value' => $value]);
            throw $e;
        }
    }
}
