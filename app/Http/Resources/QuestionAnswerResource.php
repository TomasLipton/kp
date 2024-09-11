<?php

namespace App\Http\Resources;

use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QuestionAnswer */
class QuestionAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'picture' => $this->picture,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'question_id' => $this->question_id,

            'question' => new QuestionResource($this->whenLoaded('question')),
        ];
    }
}
