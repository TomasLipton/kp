<?php

namespace App\Filament\Resources\AIQuizResource\Pages;

use App\Filament\Resources\AIQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAIQuizzes extends ListRecords
{
    protected static string $resource = AIQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
