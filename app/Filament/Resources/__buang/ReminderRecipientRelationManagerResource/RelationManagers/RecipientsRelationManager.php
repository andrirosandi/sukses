<?php

namespace App\Filament\Resources\ReminderRecipientRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('reminder_id')
                ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),
            
                Forms\Components\Select::make('contact_id')
                    ->label('Recipient')
                    ->reactive()
                    // ->afterStateUpdated(fn ($state) => dump($state))
                    ->searchable()
                    ->preload()
                    ->options(Contact::all()->pluck('name', 'id'))
                    ->required()




                    ,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('contact.contactCategory.name'),
                Tables\Columns\TextColumn::make('contact.name')->label('Name'),
                Tables\Columns\TextColumn::make('contact.account')->label('account'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
        public function beforeCreate(array $data): array
        {
            dump($data); // Debug: Lihat data sebelum disimpan
            return $data;
        }

        // public function mount(): void
        // {
        //     // dd('Relation Manager Dipanggil');
        // }


}
