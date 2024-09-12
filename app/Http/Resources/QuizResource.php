<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Quiz */
class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'questions_amount' => $this->questions_amount,
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,
            'topics_id' => $this->topics_id,

            'topics' => new TopicsResource($this->whenLoaded('topics')),
        ];
    }
}
