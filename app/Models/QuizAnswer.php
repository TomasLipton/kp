<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_answer_id',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function questionAnswer(): BelongsTo
    {
        return $this->belongsTo(QuestionAnswer::class);
    }
}
