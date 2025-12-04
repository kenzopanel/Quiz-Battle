<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'creator_session_token',
        'status',
        'max_players',
        'player_tokens',
        'expires_at',
    ];

    protected $casts = [
        'player_tokens' => 'array',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Room $room) {
            if (empty($room->code)) {
                $room->code = static::generateUniqueCode();
            }
            if (empty($room->expires_at)) {
                $room->expires_at = now()->addMinutes(30);
            }
            if (empty($room->status)) {
                $room->status = 'waiting';
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function isFull(): bool
    {
        return count($this->player_tokens ?? []) >= $this->max_players;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function canJoin(string $sessionToken): bool
    {
        return !$this->isFull() &&
            !$this->isExpired() &&
            $this->status === 'waiting' &&
            !in_array($sessionToken, $this->player_tokens ?? []);
    }

    public function addPlayer(string $sessionToken): bool
    {
        if (!$this->canJoin($sessionToken)) {
            return false;
        }

        $tokens = $this->player_tokens ?? [];
        $tokens[] = $sessionToken;
        $this->player_tokens = $tokens;
        $this->save();

        return true;
    }

    public function removePlayer(string $sessionToken): bool
    {
        $tokens = $this->player_tokens ?? [];
        $newTokens = array_values(array_filter($tokens, fn($token) => $token !== $sessionToken));

        $this->player_tokens = $newTokens;
        $this->save();

        return true;
    }
}
