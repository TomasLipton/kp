<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Question */
class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_pl' => $this->question_pl,
            'question_ru' => $this->question_ru,
            'question_type' => $this->question_type,
            'picture' => $this->picture,
            'explanation_pl' => $this->explanation_pl,
            'explanation_ru' => $this->explanation_ru,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'topics_id' => $this->topics_id,

            'topics' => new TopicsResource($this->whenLoaded('topics')),
        ];
    }
}
