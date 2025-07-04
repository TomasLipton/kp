<?php

namespace App\Filament\Resources\SocialiteUserResource\Pages;

use App\Filament\Resources\SocialiteUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialiteUsers extends ListRecords
{
    protected static string $resource = SocialiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
