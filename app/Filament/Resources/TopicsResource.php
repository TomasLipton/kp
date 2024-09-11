<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicsResource\Pages;
use App\Filament\Resources\TopicsResource\RelationManagers;
use App\Models\Topics;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TopicsResource extends Resource
{
    protected static ?string $model = Topics::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function (Set $set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required()
                    ->columnSpan('full'),
                Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),

                Forms\Components\TextInput::make('name_pl')->required()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (! $get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->reactive(),

                Forms\Components\TextInput::make('name_ru')->default('-')->required(),
                Forms\Components\TextInput::make('description_pl')->required(),
                Forms\Components\TextInput::make('description_ru')->default('-')->required(),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name_ru'),

                Forms\Components\Section::make('Image')
                    ->schema([
                        FileUpload::make('picture')
//                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('topics')
                            ->required(),
                    ])


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
                    ->searchable(),
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
