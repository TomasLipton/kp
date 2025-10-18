<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Data początkowa')
                            ->default(now()->subDays(7))
                            ->maxDate(fn ($get) => $get('endDate') ?: now()),
                        DatePicker::make('endDate')
                            ->label('Data końcowa')
                            ->default(now())
                            ->minDate(fn ($get) => $get('startDate'))
                            ->maxDate(now()),
                    ])
                    ->columns(2),
            ]);
    }
}
