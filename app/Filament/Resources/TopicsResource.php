<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicsResource\Pages;
use App\Filament\Resources\TopicsResource\RelationManagers;
use App\Models\Topics;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TopicsResource extends Resource
{
    protected static ?string $model = Topics::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')->required(),
                Forms\Components\TextInput::make('name_ru')->required(),
                Forms\Components\TextInput::make('description_ru')->required(),
                Forms\Components\TextInput::make('name_pl')->required(),
                Forms\Components\TextInput::make('description_pl')->required(),
                FileUpload::make('picture')
                    ->image()
                    ->imageEditor()
                    ->directory('topics')
                    ->required()


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
