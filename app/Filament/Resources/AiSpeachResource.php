<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiSpeachResource\Pages;
use App\Models\AiSpeach;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class AiSpeachResource extends Resource
{
    protected static ?string $model = AiSpeach::class;

    protected static ?string $slug = 'ai-speaches';

    protected static ?string $navigationIcon = 'heroicon-m-beaker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3) // Create a grid with 3 columns in total
                ->schema([
                    // First column with width of 2
                    Grid::make(1)
                        ->schema([
                            FileUpload::make('path_to_audio')
                                ->disk('local')
                                ->visibility('private')
                                ->directory('speech')
                                ->acceptedFileTypes(['audio/*']),

                            MarkdownEditor::make('text')
                                ->disabled()
                                ->required(),

                            Select::make('question_id')
                                ->relationship('question', 'question_pl'),

                            Select::make('question_answer_id')
                                ->relationship('questionAnswer', 'text', function (Builder $query) {
                                    $query->where('question_answers.text', '!=', '')
                                        ->where('question_answers.text', 'NOT REGEXP', '^[0-9]+\\.[0-9]+$')
                                        ->where('question_answers.text', 'NOT REGEXP', '^[0-9]+$');
                                }),
                        ])
                        ->columnSpan(2), // Span 2 out of 3 columns

                    // Second column with width of 1
                    Grid::make()
                        ->schema([
                            TextInput::make('voice_id')
                                ->disabled()
                                ->required(),

                            TextInput::make('type')
                                ->disabled()
                                ->required(),

                            Placeholder::make('created_at')
                                ->label('Created Date')
                                ->content(fn(?AiSpeach $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                            Placeholder::make('updated_at')
                                ->label('Last Modified Date')
                                ->content(fn(?AiSpeach $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                        ])
                        ->columnSpan(1), // Span 1 out of 3 columns
                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiSpeaches::route('/'),
            'create' => Pages\CreateAiSpeach::route('/create'),
            'edit' => Pages\EditAiSpeach::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
