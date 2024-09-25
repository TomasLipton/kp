<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ForceDeleteAction::make(),
            RestoreAction::make(),


            Action::make('Generate Voice')
                ->color('info')
                ->form([
                ])
                ->action(function (array $data) {
                    try {
                        $this->record->generateVoice();

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
//                    Mail::to($this->client)
//                        ->send(new GenericEmail(
//                            subject: $data['subject'],
//                            body: $data['body'],
//                        ));
                }),

            $this->getSaveFormAction()
                ->color('success')
                ->formId('form'),

            ReplicateAction::make()
                ->color('gray')
                ->record($this->record)
                ->beforeReplicaSaved(function (Question $question, Question $replica) {
                    $replica->question_pl = $question->question_pl . ' (Copy)';
                    return $replica;
                })
                ->after(function (Question $replica, Question $question) {
                    $question->answers()->each(function ($answer) use ($replica) {
                        $replica->answers()->create([
                            'text' => $answer->text,
                            'is_correct' => $answer->is_correct,
                            'picture' => $answer->picture,
                            'order' => $answer->order,
                        ]);
                    });
                })
                ->successRedirectUrl(fn(Question $replica): string => route('filament.admin.resources.questions.edit', [
                    'record' => $replica,
                ])),

            DeleteAction::make(),

        ];
    }


}
