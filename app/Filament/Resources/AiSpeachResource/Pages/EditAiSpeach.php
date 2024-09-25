<?php

namespace App\Filament\Resources\AiSpeachResource\Pages;

use App\Filament\Resources\AiSpeachResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAiSpeach extends EditRecord
{
    protected static string $resource = AiSpeachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            $this->getSaveFormAction()
                ->formId('form'),
        ];
    }
}
