<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Główne akcje
            $this->getSaveFormAction()
                ->color('success')
                ->icon('heroicon-o-check')
                ->formId('form')
                ->tooltip('Zapisz zmiany w pytaniu'),

            Action::make('Generate Voice')
                ->label('Generuj Audio')
                ->color('gray')
                ->icon('heroicon-o-speaker-wave')
                ->tooltip('Wygeneruj narrację audio dla tego pytania')
                ->form([])
                ->action(function (array $data) {
                    try {
                        $this->record->generateVoice();

                        Notification::make()
                            ->title('Audio zostało wygenerowane pomyślnie')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Nie udało się wygenerować audio: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            ReplicateAction::make()
                ->label('Duplikuj')
                ->color('gray')
                ->icon('heroicon-o-document-duplicate')
                ->tooltip('Utwórz kopię tego pytania ze wszystkimi odpowiedziami')
                ->record($this->record)
                ->requiresConfirmation()
                ->modalHeading('Duplikuj Pytanie')
                ->modalDescription('Czy na pewno chcesz utworzyć kopię tego pytania? Wszystkie odpowiedzi również zostaną zduplikowane.')
                ->beforeReplicaSaved(function (Question $question, Question $replica) {
                    $replica->question_pl = $question->question_pl.' (Kopia)';

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
                ->successRedirectUrl(fn (Question $replica): string => route('filament.admin.resources.questions.edit', [
                    'record' => $replica,
                ])),

            // Dodatkowe akcje w menu dropdown
            ActionGroup::make([
                DeleteAction::make()
                    ->label('Usuń')
                    ->tooltip('Przenieś pytanie do kosza'),

                RestoreAction::make()
                    ->label('Przywróć')
                    ->tooltip('Przywróć pytanie z kosza'),

                ForceDeleteAction::make()
                    ->label('Usuń trwale')
                    ->tooltip('Usuń pytanie na zawsze - nie można tego cofnąć')
                    ->modalHeading('Usuń Pytanie na Zawsze')
                    ->modalDescription('Czy na pewno chcesz trwale usunąć to pytanie? Ta akcja nie może zostać cofnięta i usunie wszystkie powiązane dane.')
                    ->requiresConfirmation(),
            ])
                ->label('Więcej Akcji')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->tooltip('Dodatkowe akcje'),
        ];
    }
}
