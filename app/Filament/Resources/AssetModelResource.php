<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetModelResource\Pages;
use App\Models\AssetModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetModelResource extends Resource
{
    protected static ?string $model = AssetModel::class;

    protected static ?string $navigationLabel = 'Models';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Setup';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('manufacturer')->maxLength(255),
            Forms\Components\TextInput::make('model_number')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('manufacturer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('model_number')->searchable(),
                Tables\Columns\TextColumn::make('assets_count')->counts('assets')->label('Assets'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssetModels::route('/'),
            'create' => Pages\CreateAssetModel::route('/create'),
            'edit' => Pages\EditAssetModel::route('/{record}/edit'),
        ];
    }
}
