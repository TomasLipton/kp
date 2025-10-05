<?php

namespace App\Filament\Resources\AIQuizResource\Pages;

use App\Filament\Resources\AIQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAIQuiz extends ViewRecord
{
    protected static string $resource = AIQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
