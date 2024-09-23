<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            $this->getSaveFormAction()
                ->formId('form'),
            ReplicateAction::make()
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
                ]))
        ];
    }


}
