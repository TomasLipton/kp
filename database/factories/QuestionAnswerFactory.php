<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuestionAnswerFactory extends Factory
{
    protected $model = QuestionAnswer::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->text(),
            'picture' => $this->faker->word(),
            'order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'question_id' => Question::factory(),
        ];
    }
}
