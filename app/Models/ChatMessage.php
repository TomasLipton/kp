<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'a_i_quiz_id',
        'role',
        'content',
        'tool_name',
        'tool_call',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'tool_call' => 'array',
            'metadata' => 'array',
        ];
    }

    public function aiQuiz(): BelongsTo
    {
        return $this->belongsTo(AIQuiz::class, 'a_i_quiz_id');
    }
}
