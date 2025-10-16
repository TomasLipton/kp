<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicsResource\Pages;
use App\Models\Topics;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TopicsResource extends Resource
{
    protected static ?string $model = Topics::class;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $label = 'Temat';

    protected static ?string $pluralLabel = 'Tematy';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function (Set $set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required()
                    ->disabled(fn ($record) => $record !== null)
                    ->dehydrated()
                    ->columnSpan('full'),
                Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Polish (PL)')
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name_pl')
                                            ->label('Name')
                                            ->required()
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                                if (! $get('is_slug_changed_manually') && filled($state)) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            })
                                            ->reactive(),

                                        Forms\Components\RichEditor::make('description_pl')
                                            ->label('Description')
                                            ->required(),

                                        Forms\Components\Textarea::make('seo_description_pl')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(6)
                                            ->helperText('Max 160 characters for search engines')
                                            ->placeholder('Brief description for search results...'),
                                    ]),

                                Forms\Components\Section::make('Russian (RU)')
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name_ru')
                                            ->label('Name')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\RichEditor::make('description_ru')
                                            ->label('Description')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\Textarea::make('seo_description_ru')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Max 160 characters for search engines')
                                            ->placeholder('Brief description for search results...'),
                                    ]),

                                Forms\Components\Section::make('Belarusian (BY)')
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name_by')
                                            ->label('Name')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\RichEditor::make('description_by')
                                            ->label('Description')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\Textarea::make('seo_description_by')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Max 160 characters for search engines')
                                            ->placeholder('Brief description for search results...'),
                                    ]),

                                Forms\Components\Section::make('Ukrainian (UK)')
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name_uk')
                                            ->label('Name')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\RichEditor::make('description_uk')
                                            ->label('Description')
                                            ->default('-')
                                            ->required(),

                                        Forms\Components\Textarea::make('seo_description_uk')
                                            ->label('SEO Description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Max 160 characters for search engines')
                                            ->placeholder('Brief description for search results...'),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Settings')
                                    ->schema([
                                        Forms\Components\Select::make('parent_id')
                                            ->relationship('parent', 'name_ru'),

                                        Forms\Components\Select::make('difficulty')
                                            ->label('Difficulty Level')
                                            ->options([
                                                'easy' => 'Easy',
                                                'medium' => 'Medium',
                                                'hard' => 'Hard',
                                            ])
                                            ->default('medium')
                                            ->required(),

                                        Forms\Components\Toggle::make('isVisibleToPublic')
                                            ->label('Visible to Public')
                                            ->default(true),
                                    ]),

                                Forms\Components\Section::make('Image')
                                    ->schema([
                                        FileUpload::make('picture')
//                            ->disk('public')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('topics')
                                            ->required(),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->width('100px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_pl')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ru')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->badge(),
                Tables\Columns\TextColumn::make('difficulty')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'easy' => 'success',
                        'medium' => 'warning',
                        'hard' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('isVisibleToPublic')
                    ->label('Public')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopics::route('/create'),
            'edit' => Pages\EditTopics::route('/{record}/edit'),
        ];
    }
}
