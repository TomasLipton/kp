<?php

namespace App\Filament\Resources\QuestionAnswerResource\Pages;

use App\Filament\Resources\QuestionAnswerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestionAnswer extends CreateRecord
{
    protected static string $resource = QuestionAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
