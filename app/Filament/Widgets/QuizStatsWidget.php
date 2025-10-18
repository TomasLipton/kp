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

        // Get chart data for the last 7 days
        $authorizedChartData = $this->getChartData(true);
        $unauthorizedChartData = $this->getChartData(false);

        return [
            Stat::make('Quizy autoryzowanych użytkowników', $authorizedQuizzes)
                ->description('Quizy rozwiązane przez zalogowanych użytkowników')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('success')
                ->chart($authorizedChartData),

            Stat::make('Quizy nieautoryzowanych użytkowników', $unauthorizedQuizzes)
                ->description('Quizy rozwiązane przez gości')
                ->descriptionIcon('heroicon-m-user')
                ->color('warning')
                ->chart($unauthorizedChartData),
        ];
    }

    protected function getChartData(bool $authorized): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();

            $count = Quiz::query()
                ->when($authorized, fn ($query) => $query->whereNotNull('user_id'))
                ->when(! $authorized, fn ($query) => $query->whereNull('user_id'))
                ->whereDate('created_at', $date)
                ->count();

            $data[] = $count;
        }

        return $data;
    }
}
