<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AIQuiz extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'topic_id',
        'speed',
        'difficulty',
        'gender',
        'voice',
        'ephemeral_key',
        'ephemeral_key_expiry',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'ephemeral_key_expiry' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topics::class, 'topic_id');
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'a_i_quiz_id');
    }

    public function isExpired(): bool
    {
        return $this->ephemeral_key_expiry && $this->ephemeral_key_expiry < time();
    }
}
