<?php

namespace App\Filament\Resources\SocialiteUserResource\Pages;

use App\Filament\Resources\SocialiteUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSocialiteUser extends EditRecord
{
    protected static string $resource = SocialiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
