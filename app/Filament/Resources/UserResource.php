<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 9001;

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()->can('user.view');
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()->can('user.create');
    }

    public static function canEdit(Model $record): bool
    {
        return Filament::auth()->user()->can('user.edit');
    }

    public static function canDelete(Model $record): bool
    {
        return Filament::auth()->user()->can('user.delete');
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('userid')
                    ->label('User ID')
                    // ->disabled()
                    ->required(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->maxLength(255)
                    // ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    // ->dehydrated(false)
                    // ->hiddenOn('edit')
                    ,
                Select::make('role_id')
                    ->label('Role')
                    
                    ->searchable()
                    ->preload()
                    ->relationship('roles', 'name')
                    ->dehydrated(false) // Tambahkan ini
                
                
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('userid')->label('User ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')->sortable(),
                
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
