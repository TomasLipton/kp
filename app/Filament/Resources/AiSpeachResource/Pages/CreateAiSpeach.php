<?php

namespace App\Filament\Resources\AiSpeachResource\Pages;

use App\Filament\Resources\AiSpeachResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAiSpeach extends CreateRecord
{
    protected static string $resource = AiSpeachResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
