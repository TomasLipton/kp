<?php

namespace Database\Factories;

use App\Models\AiSpeach;
use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AiSpeachFactory extends Factory
{
    protected $model = AiSpeach::class;

    public function definition(): array
    {
        return [
            'path_to_audio' => $this->faker->word(),
            'type' => $this->faker->word(),
            'voice_id' => $this->faker->word(),
            'text' => $this->faker->text(),
            'voice_settings' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'question_id' => Question::factory(),
            'question_answer_id' => QuestionAnswer::factory(),
        ];
    }
}
