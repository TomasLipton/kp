<?php

namespace App\Filament\Resources\AIQuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChatMessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'chatMessages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('role')
                    ->options([
                        'user' => 'User',
                        'assistant' => 'Assistant',
                        'system' => 'System',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('content')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('tool_name')
                    ->maxLength(255)
                    ->label('Tool Name'),

                Forms\Components\KeyValue::make('tool_call')
                    ->label('Tool Call Data'),

                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadata'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user' => 'primary',
                        'assistant' => 'success',
                        'system' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('content')
                    ->limit(100)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tool_name')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'assistant' => 'Assistant',
                        'system' => 'System',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'asc');
    }
}
