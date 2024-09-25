<?php

namespace App\Filament\Resources\AiSpeachResource\Pages;

use App\Filament\Resources\AiSpeachResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiSpeaches extends ListRecords
{
    protected static string $resource = AiSpeachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
