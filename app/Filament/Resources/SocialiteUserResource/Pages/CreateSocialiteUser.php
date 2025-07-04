<?php

namespace App\Filament\Resources\SocialiteUserResource\Pages;

use App\Filament\Resources\SocialiteUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialiteUser extends CreateRecord
{
    protected static string $resource = SocialiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
