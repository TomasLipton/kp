<?php

namespace Database\Factories;

use App\Models\QuestionAnswer;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuizAnswerFactory extends Factory
{
    protected $model = QuizAnswer::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'quiz_id' => Quiz::factory(),
            'question_answer_id' => QuestionAnswer::factory(),
        ];
    }
}
