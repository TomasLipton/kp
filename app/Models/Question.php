<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'question_pl',
        'question_ru',
        'question_type',
        'picture',
        'explanation_pl',
        'explanation_ru',
        'topics_id',
    ];

    public function topics(): BelongsTo
    {
        return $this->belongsTo(Topics::class);
    }
}
