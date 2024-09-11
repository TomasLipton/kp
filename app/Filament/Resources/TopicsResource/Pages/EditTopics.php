<?php

namespace App\Filament\Resources\TopicsResource\Pages;

use App\Filament\Resources\TopicsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopics extends EditRecord
{
    protected static string $resource = TopicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
