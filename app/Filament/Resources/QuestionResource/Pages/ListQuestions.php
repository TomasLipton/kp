<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('Generate Voice')
                ->color('info')
                ->form([
                ])
                ->action(function (array $data) {
                    try {
                        $questions = Question::doesntHave('aiSpeach')->chunk(100, function ($questions) {
                            foreach ($questions as $question) {
                                $question->generateVoice();
                            }
                        });
                        Notification::make()
                            ->title('Voice generated')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to generate voice: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
