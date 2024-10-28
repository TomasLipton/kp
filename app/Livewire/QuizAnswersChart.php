<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class QuizAnswersChart extends ChartWidget
{
    protected static ?string $heading = 'Poprawne i niepoprawne odpowiedzi';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Fetch correct and incorrect answers counts per month
        $correctAnswers = Auth::user()->quizzes()
            ->join('quiz_answers', 'quizzes.id', '=', 'quiz_answers.quiz_id')
            ->join('question_answers', 'quiz_answers.question_answer_id', '=', 'question_answers.id')
            ->selectRaw('MONTH(quiz_answers.created_at) as month, COUNT(*) as count')
            ->where('question_answers.is_correct', true)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $incorrectAnswers = Auth::user()->quizzes()
            ->join('quiz_answers', 'quizzes.id', '=', 'quiz_answers.quiz_id')
            ->join('question_answers', 'quiz_answers.question_answer_id', '=', 'question_answers.id')
            ->selectRaw('MONTH(quiz_answers.created_at) as month, COUNT(*) as count')
            ->where('question_answers.is_correct', false)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0 counts
        $months = range(1, 12);
        $correctData = array_map(fn($month) => $correctAnswers[$month] ?? 0, $months);
        $incorrectData = array_map(fn($month) => $incorrectAnswers[$month] ?? 0, $months);

        return [
            'datasets' => [
                [
                    'label' => 'Poprawne odpowiedzi',
                    'data' => $correctData,
                    'borderColor' => 'green',
                    'backgroundColor' => 'rgba(0, 128, 0, 0.3)',
                ],
                [
                    'label' => 'Niepoprawne odpowiedzi',
                    'data' => $incorrectData,
                    'borderColor' => 'red',
                    'backgroundColor' => 'rgba(255, 0, 0, 0.3)',
                ],
            ],
            'labels' => ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
