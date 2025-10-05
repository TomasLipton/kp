<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AIQuizResource\Pages;
use App\Filament\Resources\AIQuizResource\RelationManagers;
use App\Models\AIQuiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AIQuizResource extends Resource
{
    protected static ?string $model = AIQuiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Ai Quiz';

    protected static ?string $label = 'Ai Quiz';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quiz Settings')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('topic_id')
                            ->relationship('topic', 'name_pl')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\Select::make('speed')
                            ->options([
                                'slow' => 'Slow',
                                'normal' => 'Normal',
                                'fast' => 'Fast',
                            ])
                            ->default('normal')
                            ->required(),

                        Forms\Components\Select::make('difficulty')
                            ->options([
                                'easy' => 'Easy',
                                'medium' => 'Medium',
                                'hard' => 'Hard',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->default('female')
                            ->required(),

                        Forms\Components\TextInput::make('voice')
                            ->label('TTS Voice Identifier')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->options([
                                'preparing' => 'Preparing',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->default('preparing')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('OpenAI Realtime API')
                    ->schema([
                        Forms\Components\TextInput::make('ephemeral_key')
                            ->label('Ephemeral Key')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('ephemeral_key_expiry')
                            ->label('Ephemeral Key Expiry (Unix Timestamp)')
                            ->numeric()
                            ->helperText('Example: 1717703267'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('topic.name_pl')
                    ->label('Topic')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'preparing' => 'warning',
                        'active' => 'info',
                        'in_progress' => 'primary',
                        'completed' => 'success',
                    }),

                Tables\Columns\TextColumn::make('speed')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'slow' => 'warning',
                        'normal' => 'success',
                        'fast' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('difficulty')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'easy' => 'success',
                        'medium' => 'warning',
                        'hard' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('gender')
                    ->badge(),

                Tables\Columns\IconColumn::make('ephemeral_key')
                    ->label('Has Key')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'preparing' => 'Preparing',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'easy' => 'Easy',
                        'medium' => 'Medium',
                        'hard' => 'Hard',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChatMessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAIQuizzes::route('/'),
            'create' => Pages\CreateAIQuiz::route('/create'),
            'view' => Pages\ViewAIQuiz::route('/{record}'),
            'edit' => Pages\EditAIQuiz::route('/{record}/edit'),
        ];
    }
}
