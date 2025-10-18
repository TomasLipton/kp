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

        return [
            Stat::make('Łączna liczba pytań', $totalQuestions)
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('info'),
        ];
    }
}
