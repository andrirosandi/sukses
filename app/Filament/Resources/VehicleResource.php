<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?int $navigationSort = 1000;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('vehicle_category_id')
                    ->relationship('vehicleCategory', 'name')
                    ->required(),
                Forms\Components\TextInput::make('merk'),
                Forms\Components\TextInput::make('type'),
                Forms\Components\TextInput::make('license_plate')
                    ->required(),
                Forms\Components\TextInput::make('registered_owner'),
                Forms\Components\TextInput::make('assigned_user'),
                Forms\Components\DatePicker::make('tax_expiry_date')
                    ->required(),
                Forms\Components\DatePicker::make('plate_expiry_date')
                    ->required(),
                Forms\Components\Toggle::make('remind_me')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicleCategory.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registered_owner')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_user')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_expiry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plate_expiry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('remind_me')
                    ->boolean(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
