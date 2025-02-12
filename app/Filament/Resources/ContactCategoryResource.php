<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactCategoryResource\Pages;
use App\Filament\Resources\ContactCategoryResource\RelationManagers;
use App\Models\ContactCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactCategoryResource extends Resource
{
    protected static ?string $model = ContactCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 9003;
    public static function getModelLabel(): string
    {
        return 'Contact Category'; // Label untuk satu item
    }

    public static function getPluralModelLabel(): string
    {
        return 'Contact Categories'; // Label untuk banyak item
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('created_by')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('updated_by')
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListContactCategories::route('/'),
            'create' => Pages\CreateContactCategory::route('/create'),
            'edit' => Pages\EditContactCategory::route('/{record}/edit'),
        ];
    }
}
