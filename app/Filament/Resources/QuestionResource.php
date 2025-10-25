<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers\AiSpeachRelationManager;
use App\Models\Question;
use App\Models\Topics;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group as TableGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $slug = 'questions';

    protected static ?string $label = 'Pytanie';

    protected static ?string $pluralLabel = 'Pytania';

    protected static ?string $navigationIcon = 'heroicon-c-question-mark-circle';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()
                    ->schema([
                        Section::make('Pytanie w języku polskim')
                            ->collapsible()
                            ->schema([
                                TextInput::make('question_pl')
                                    ->label('Pytanie w języku polskim')
                                    ->required(),
                                Textarea::make('explanation_pl'),
                            ]),

                        //                        Section::make('Pytanie w języku rosyjskim')
                        //                            ->collapsible()
                        //                            ->collapsed()
                        //                            ->schema([
                        //                                TextInput::make('question_ru')
                        //                                    ->label('Pytanie w języku rosyjskim'),
                        //                                TextInput::make('explanation_ru'),
                        //                            ]),

                        Repeater::make('answers')
                            ->reactive()
                            ->helperText("For date+month use format 'DD.MM' e.g. '01.01'. For date+month+year use format 'DD.MM.YYYY' e.g. '01.01.2022'. For year use format 'YYYY' e.g. '2022'. For year, date+month, and date+month+year add 1 incorrect answer with any value.")
                            ->relationship()
                            ->orderColumn('order')
                            ->grid(2)
                            ->defaultItems(4)
                            ->maxItems(fn (Get $get): int => in_array($get('question_type'), ['year', 'date_month', 'date_month_year', 'number']) ? 2 : 5)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
//                            ->itemLabel(fn(array $state): ?string => '# ' . $state['id'] ?? null)
                            ->schema([
                                Toggle::make('is_correct'),
                                Textarea::make('text')
                                    ->label('Odpowiedź w języku polskim')
                                    ->required(),
                                //                                FileUpload::make('picture')
                                //                                    ->image()
                                //                                    ->imageEditor()
                                //                                    ->label('Zdjęcie'),
                            ])
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    // Check if at least 2 answers exist
                                    if (count($value) < 2) {
                                        $fail('Należy podać co najmniej 2 odpowiedzi.');
                                        Notification::make()
                                            ->title('Należy podać co najmniej 2 odpowiedzi.')
                                            ->danger()
                                            ->send();
                                    }

                                    // Check if at least one is_correct toggle is true
                                    if (! collect($value)->contains('is_correct', true)) {
                                        $fail('At least one option must be set as correct.');
                                        Notification::make()
                                            ->title('At least one answer must be set as correct.')
                                            ->danger()
                                            ->send();
                                    }
                                },
                            ]),

                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        //                        ToggleButtons::make('is_reviewed')
                        //                            ->options([
                        //                                '1' => 'Tak',
                        //                                '0' => 'Nie',
                        //                            ])
                        //                            ->colors([
                        //                                '1' => 'success',
                        //                                '0' => 'danger',
                        //                            ])
                        //                            ->inline()
                        //                            ->default(1)->required(),

                        ToggleButtons::make('question_type')
                            ->label('Typ pytania')
                            ->default('single_text')
                            ->options([
                                'single_text' => 'Single Text',
                                'date_month' => 'Date Month',
                                'date_month_year' => 'Date Month Year',
                                'year' => 'Year',
                                'number' => 'Number',
                                'multi_text' => 'Multi Text',
                            ])
                            ->inline()
                            ->required()
                            ->reactive()
                            ->disableOptionWhen(fn (string $value): bool => in_array($value, ['multi_text']))->afterStateUpdated(function ($state, callable $set) {
                                if (in_array($state, ['year', 'date_month', 'date_month_year', 'number'])) {
                                    // Create 2 default answer items
                                    $set('answers', [
                                        ['text' => '', 'is_correct' => true, 'order' => 1],
                                        ['text' => '', 'is_correct' => false, 'order' => 2],
                                    ]);
                                }
                            }),

                        ToggleButtons::make('topics_id')
                            ->label('Tematy')
                            ->options(Topics::all()->pluck('name_pl', 'id'))
                            ->required(),

                        Select::make('user_id')
                            ->label('Author')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->nullable()
                            ->hiddenOn('create')
                            ->default(auth()->id()),

                        FileUpload::make('picture')
                            ->image()
                            ->imageEditor()
                            ->label('Zdjęcie'),
                    ])->columnSpan(['lg' => 1]),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?Question $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?Question $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('Picture')->label('Zdjęcie')->boolean(fn () => true)->default(fn (Question $question) => $question->picture)->sortable(),
                IconColumn::make('is_reviewed')
                    ->label('Zweryfikowano')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean()
                    ->sortable(),
                IconColumn::make('Voice')->label('Audio')->boolean(fn () => true)->default(fn (Question $question) => $question->aiSpeach()->count() > 0),
                TextColumn::make('question_pl')
                    ->searchable()
                    ->label('Pytanie (PL)')
                    ->extraAttributes([
                        'style' => 'max-width:260px',
                    ])
                    ->searchable()
                    ->wrap(),

                TextColumn::make('question_type')
                    ->badge()
                    ->searchable(isIndividual: true)
                    ->color(fn (string $state): string => match ($state) {
                        'single_text' => 'info',
                        'date_month' => 'warning',
                        'date_month_year' => 'warning',
                        'year' => 'success',
                        'multi_text' => 'danger',
                        'number' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'single_text' => 'Single Text',
                        'date_month' => 'Date Month',
                        'date_month_year' => 'Date Month Year',
                        'year' => 'Year',
                        'multi_text' => 'Multi Text',
                        'number' => 'Number',
                        default => $state,
                    })
                    ->label('Typ')->sortable(),

                TextColumn::make('topics.name_pl')
                    ->label('Temat')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->groups([
                TableGroup::make('question_type')
                    ->label('Typ pytania'),
                TableGroup::make('topics.name_pl')
                    ->label('Temat'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('topics_id')
                    ->relationship('topics', 'name_pl')
                    ->label('Temat'),
                SelectFilter::make('question_type')
                    ->label('Typ pytania')
                    ->options([
                        'single_text' => 'Single Text',
                        'date_month' => 'Date Month',
                        'date_month_year' => 'Date Month Year',
                        'year' => 'Year',
                        'number' => 'Number',
                        'multi_text' => 'Multi Text',
                    ]),

            ])
            ->actions([
                ReplicateAction::make()
                    ->color('warning')
                    ->iconButton()
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
                    ->beforeReplicaSaved(function (Question $question, Question $replica) {
                        $replica->question_pl = $question->question_pl.' (Copy)';

                        return $replica;
                    }),
                EditAction::make()
                    ->color('info')
                    ->iconButton(),
                DeleteAction::make()->iconButton(),
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
            ->persistFiltersInSession()
            ->poll('5s')
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['topics']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['topics.name_pl'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->topics) {
            $details['Topics'] = $record->topics->name_pl;
        }

        return $details;
    }

    public static function getRelations(): array
    {
        return [
            AiSpeachRelationManager::class,
        ];
    }
}
