<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiSpeach extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'path_to_audio',
        'type',
        'question_id',
        'question_answer_id',
        'voice_id',
        'text',
        'voice_settings',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function questionAnswer(): BelongsTo
    {
        return $this->belongsTo(QuestionAnswer::class);
    }

    protected function casts(): array
    {
        return [
            'voice_settings' => 'array',
        ];
    }
}
