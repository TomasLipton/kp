<?php

namespace App\Filament\Widgets;

use App\Models\Quiz;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subDays(7);
        $endDate = $this->filters['endDate'] ?? now();

        // Apply date range filter to the queries
        $authorizedQuizzes = Quiz::whereNotNull('user_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $unauthorizedQuizzes = Quiz::whereNull('user_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Get chart data for the selected date range
        $authorizedChartData = $this->getChartData(true, $startDate, $endDate);
        $unauthorizedChartData = $this->getChartData(false, $startDate, $endDate);

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

    protected function getChartData(bool $authorized, $startDate, $endDate): array
    {
        $data = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end);

        // Limit to maximum 30 data points for chart readability
        $interval = max(1, ceil($daysDiff / 30));

        for ($i = 0; $i <= $daysDiff; $i += $interval) {
            $date = $start->copy()->addDays($i);

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
