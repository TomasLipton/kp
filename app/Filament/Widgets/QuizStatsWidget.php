<?php

namespace App\Filament\Widgets;

use App\Models\Quiz;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $authorizedQuizzes = Quiz::whereNotNull('user_id')->count();
        $unauthorizedQuizzes = Quiz::whereNull('user_id')->count();
        $totalQuizzes = $authorizedQuizzes + $unauthorizedQuizzes;

        return [
            Stat::make('Quizy autoryzowanych użytkowników', $authorizedQuizzes)
                ->description('Quizy rozwiązane przez zalogowanych użytkowników')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('success'),

            Stat::make('Quizy nieautoryzowanych użytkowników', $unauthorizedQuizzes)
                ->description('Quizy rozwiązane przez gości')
                ->descriptionIcon('heroicon-m-user')
                ->color('warning'),
        ];
    }
}
