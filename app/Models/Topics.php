<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topics extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name_ru',
        'description_ru',
        'name_pl',
        'description_pl',
        'name_by',
        'description_by',
        'name_uk',
        'description_uk',
        'picture',
        'parent_id',
        'isVisibleToPublic',
        'difficulty',
    ];

    protected function casts(): array
    {
        return [
            'isVisibleToPublic' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
