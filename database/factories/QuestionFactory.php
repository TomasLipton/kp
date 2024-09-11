<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Topics;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'question_pl' => $this->faker->word(),
            'question_ru' => $this->faker->word(),
            'question_type' => $this->faker->word(),
            'picture' => $this->faker->word(),
            'explanation_pl' => $this->faker->word(),
            'explanation_ru' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'topics_id' => Topics::factory(),
        ];
    }
}
