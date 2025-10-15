<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    protected static ?string $title = 'Zaliczone Quizy';

    protected static ?string $label = 'Quiz';

    protected static ?string $pluralLabel = 'Quizy';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('completed_at'))
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('topics.name_pl')
                    ->label('Temat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('questions_amount')
                    ->label('Liczba pytań')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Odpowiedzi')
                    ->counts('answers')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Data ukończenia')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->completed_at?->diffForHumans()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data utworzenia')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'test' => 'Test',
                        'exam' => 'Egzamin',
                        'practice' => 'Praktyka',
                    ]),

                Tables\Filters\SelectFilter::make('topics_id')
                    ->relationship('topics', 'name_pl')
                    ->label('Temat'),
            ])
            ->headerActions([
                // Remove create action for passed quizzes (view only)
            ])
            ->actions([
                // No actions needed for view-only relation
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('completed_at', 'desc')
            ->emptyStateHeading('Brak zaliczonych quizów')
            ->emptyStateDescription('Ten użytkownik nie zaliczył jeszcze żadnego quizu.');
    }
}
