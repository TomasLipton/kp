<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Topics;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'type' => $this->faker->word(),
            'questions_amount' => $this->faker->randomNumber(),
            'is_completed' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'topics_id' => Topics::factory(),
        ];
    }
}
