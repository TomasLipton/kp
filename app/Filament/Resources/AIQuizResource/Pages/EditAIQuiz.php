<?php

namespace App\Filament\Resources\AIQuizResource\Pages;

use App\Filament\Resources\AIQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAIQuiz extends EditRecord
{
    protected static string $resource = AIQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
