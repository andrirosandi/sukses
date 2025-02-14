<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MessageProvider;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MessageProviderResource\Pages;
use App\Filament\Resources\MessageProviderResource\RelationManagers;

class MessageProviderResource extends Resource
{
    protected static ?string $model = MessageProvider::class;
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 9002;
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique()
                    ->columnSpanFull(),

                    Repeater::make('config')
    ->label('Configuration')
    ->schema([
        Grid::make(2)->schema([
            TextInput::make('key')
                ->label('Key')
                ->required(),
            TextInput::make('value')
                ->label('Value')
                ->required(),
        ]),
    ])
    ->addable(true)
    ->deletable(true)
    ->reorderable(true)
    ->columnSpanFull(),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListMessageProviders::route('/'),
            'create' => Pages\CreateMessageProvider::route('/create'),
            'edit' => Pages\EditMessageProvider::route('/{record}/edit'),
        ];
    }
}
