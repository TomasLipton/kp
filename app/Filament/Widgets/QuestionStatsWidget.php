<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuestionStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalQuestions = Question::count();
        $reviewedQuestions = Question::where('is_reviewed', 1)->count();
        $unreviewed = $totalQuestions - $reviewedQuestions;

        return [
            Stat::make('Łączna liczba pytań', $totalQuestions)
                ->description('Wszystkie pytania w bazie danych')
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('info'),

            Stat::make('Pytania zweryfikowane', $reviewedQuestions)
                ->description('Pytania sprawdzone i zatwierdzone')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pytania niezweryfikowane', $unreviewed)
                ->description('Pytania oczekujące na weryfikację')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
}