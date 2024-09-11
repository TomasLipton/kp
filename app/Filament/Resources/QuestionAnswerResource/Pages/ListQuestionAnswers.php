<?php

namespace App\Filament\Resources\QuestionAnswerResource\Pages;

use App\Filament\Resources\QuestionAnswerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuestionAnswers extends ListRecords
{
    protected static string $resource = QuestionAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
