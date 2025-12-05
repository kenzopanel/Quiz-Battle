<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'timeout_seconds',
        'per_question_time',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function getFormattedTimeout(): string
    {
        $minutes = floor($this->timeout_seconds / 60);
        $seconds = $this->timeout_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
