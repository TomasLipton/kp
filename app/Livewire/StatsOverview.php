<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalQuizzes = Auth::user()->quizzes()->count();
        $totalAnswers = Auth::user()->quizzes()->withCount('answers')->get()->sum('answers_count');

        // Calculate data for last week and this week
        $lastWeekQuizzes = Auth::user()->quizzes()->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $thisWeekQuizzes = Auth::user()->quizzes()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $lastWeekAnswers = Auth::user()->quizzes()->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->withCount('answers')->get()->sum('answers_count');
        $thisWeekAnswers = Auth::user()->quizzes()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->withCount('answers')->get()->sum('answers_count');

        // Calculate percentage increase
        $quizIncrease = $lastWeekQuizzes > 0 ? round((($thisWeekQuizzes - $lastWeekQuizzes) / $lastWeekQuizzes) * 100, 2) : 0;
        $answerIncrease = $lastWeekAnswers > 0 ? round((($thisWeekAnswers - $lastWeekAnswers) / $lastWeekAnswers) * 100, 2) : 0;

        $weeklyQuizCounts = Auth::user()->quizzes()
            ->selectRaw('YEARWEEK(created_at) as week, COUNT(*) as count')
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('count')
            ->toArray();

        $weeklyAnswerCounts = Auth::user()->quizzes()
            ->join('quiz_answers', 'quizzes.id', '=', 'quiz_answers.quiz_id')
            ->selectRaw('YEARWEEK(quiz_answers.created_at) as week, COUNT(quiz_answers.id) as count')
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('count')
            ->toArray();

        // Calculate average duration (assuming you have a `started_at` field)
        $quizzesWithDuration = Auth::user()->quizzes()
            ->selectRaw('TIMESTAMPDIFF(SECOND, quizzes.created_at, quizzes.completed_at) as duration')
            ->whereNotNull('completed_at')
            ->pluck('duration');

        $averageDuration = $quizzesWithDuration->isNotEmpty() ? round($quizzesWithDuration->average(), 2) : 0;

        // Convert seconds to a more readable format (e.g., minutes and seconds)
        $averageDurationFormatted = gmdate('i:s', $averageDuration);

        return [
            Stat::make(__('Wszystkie testy'), $totalQuizzes)
                ->description("{$quizIncrease}% wzrost")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($weeklyQuizCounts),

            Stat::make(__('Współczynnik odpowiedzi'), $totalAnswers)
                ->description("{$answerIncrease}% wzrost")
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart($weeklyAnswerCounts),

            Stat::make(__('Średni czas trwania quizu'), $averageDurationFormatted)
                ->description('Czas średni w minutach i sekundach')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            //            Stat::make('Unique views', '192.1k')
            //                ->description('32k increase')
            //                ->descriptionIcon('heroicon-m-arrow-trending-up')
            //                ->chart([7, 2, 10, 3, 15, 4, 17])
            //                ->color('success'),
        ];
    }
}
