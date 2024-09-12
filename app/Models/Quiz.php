<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'questions_amount',
        'is_completed',
        'topics_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topics(): BelongsTo
    {
        return $this->belongsTo(Topics::class);
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
