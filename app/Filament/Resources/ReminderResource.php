<?php

namespace App\Filament\Resources;

//use Log;
use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use App\Models\Reminder;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RecordCategory;
use Filament\Resources\Resource;
use App\Models\ReminderRecipient;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReminderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReminderResource\RelationManagers;
use App\Filament\Resources\ReminderRecipientRelationManagerResource\RelationManagers\RecipientsRelationManager;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1002;
    public static function form(Form $form): Form
    {
        // $tableName = 'vehicles';
        // $columns = Schema::getColumnListing($tableName);
        // $excludedColumns = ['id', 'created_at', 'updated_at'];
        // $variables = array_map(fn ($col) => "{" . $col . "}", array_diff($columns, $excludedColumns));
        // $helperText = "Available variables: " . implode(" ", $variables);
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('record_category_id')
                    ->relationship('recordCategory', 'name')
                    ->reactive() // Agar memicu perubahan pada helperText
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('available_variables', RecordCategory::getTableColumns($state));
                        $set('reference_date', RecordCategory::getTableColumns($state, ['date', 'datetime']));
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        $set('available_variables', RecordCategory::getTableColumns($state));
                        $set('reference_date', RecordCategory::getTableColumns($state, ['date', 'datetime']));
                    })
                    ->live()

                    ->required(),
                Select::make('reference_date_column')
                    ->label('Reference Date')
                    // ->options(fn ($get) => self::getReferenceDateOptions(explode(':', $get('ref'))[0] ?? ''))
                    ->options(fn($get) => $get('reference_date'))
                    ->searchable()
                    ->required(),


                Forms\Components\Select::make('repeat_every')
                    ->options([
                        '1y' => 'Year',
                        '1m' => 'Month'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('on_days')
                    ->helperText('Enter the days before the event when the reminder should be triggered. 
                        Use a comma-separated list of negative numbers, e.g., "-1, -2, 0".
                        means 1 day before the event. 2 days before the event and the same day as the event.')
                    ->required(),
                Forms\Components\TimePicker::make('on_time')
                    ->required(),
                Forms\Components\Textarea::make('reminder_message')
                    ->columnSpanFull()
                    ->helperText(
                        fn($get) =>
                        "Available variables: " . implode(", ", array_map(fn($col) => "{" . $col . "}", $get('available_variables') ?? []))
                    )
                    ->default(fn($get) => RecordCategory::getTableColumns($get('record_category_id'))),
                Select::make('recipient')
                    ->label('Recipients')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(
                        Contact::with('contactCategory')->get()->mapWithKeys(function ($contact) {
                            // dump($contact);
                            return [$contact->id => "{$contact->name} - {$contact->account} ({$contact->contactCategory->name})"];
                        })->toArray()
                    )
                    ->dehydrated(false) // Tambahkan ini
                    // ->relationship('contacts','name')
                    ->columnSpanFull()
                    ->afterStateHydrated(function ($state, callable $set, $livewire) {
                        // Ambil ID dari relasi recipients dan set ke state
                        if ($livewire->record) {
                            # code...
                            $set('recipient', $livewire->record->contacts->pluck('id')->toArray());
                        }
                    })
                    ,
                    Forms\Components\Toggle::make('enabled')
                    ->required(),
                    Forms\Components\Repeater::make('config')
    ->label('Configuration')
    ->schema([
        Grid::make(2) // 2 kolom: Key | Value
            ->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('value')
                    ->label('Value')
                    ->required()
                    ->columnSpan(1),
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
                Tables\Columns\IconColumn::make('enabled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recordCategory.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_date_column')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repeat_every')
                    ->searchable(),
                Tables\Columns\TextColumn::make('on_days')
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




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }
    // protected static function mutateFormDataBeforeFill(array $data): array
    // {
    //     // $data['recipient'] = Reminder::find($data['id'])?->contacts()->pluck('id')->toArray();
    //     dump($data);
    //     return $data;
    // }
}
