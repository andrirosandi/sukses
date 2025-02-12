<?php

namespace App\Filament\Resources;

//use Log;
use Filament\Forms;
use Filament\Tables;
use App\Models\Reminder;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RecordCategory;
use Filament\Resources\Resource;
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

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Manage';
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
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('available_variables', static::getTableColumns($state))
                    )
                    ->afterStateHydrated(fn ($state, callable $set) => 
                        $set('available_variables', static::getTableColumns($state))
                    )
                    ->live()
                    
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
                        ->helperText(fn ($get) => 
                    "Available variables: " . implode(", ", array_map(fn ($col) => "{" . $col . "}", $get('available_variables') ?? []))
                )
                ->default(fn ($get) => static::getTableColumns($get('record_category_id'))),
                Forms\Components\Toggle::make('enabled')
                    ->required(),
                
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }
    public static function getTableColumns(?int $recordCategoryId): array
{
    if (!$recordCategoryId) {
        \Log::error("record_category_id is null");
        return [];
    }

    $recordCategory = RecordCategory::find($recordCategoryId);
    if (!$recordCategory || !$recordCategory->name) {
        \Log::error("RecordCategory tidak ditemukan untuk ID: " . $recordCategoryId);
        return [];
    }

    $modelClassName = "App\\Models\\" . $recordCategory->name;
    if (!class_exists($modelClassName)) {
        \Log::error("Model class tidak ditemukan: " . $modelClassName);
        return [];
    }

    if (!$recordCategoryId) {
        return [];
    }

    $recordCategory = RecordCategory::find($recordCategoryId);
    if (!$recordCategory || !$recordCategory->name) {
        return [];
    }

    $modelClassName = "App\\Models\\" . $recordCategory->name;

    if (!class_exists($modelClassName)) {
        \Log::error('Model class does not exist', ['class' => $modelClassName]);
        return [];
    }

    $modelInstance = app($modelClassName);

    if (!$modelInstance instanceof Model) {
        \Log::error('Model instance is not a valid Eloquent model', ['class' => $modelClassName]);
        return [];
    }

    try {
        $tableName = $modelInstance->getTable();
        if (!Schema::hasTable($tableName)) {
            \Log::error('Table does not exist in the database', ['table' => $tableName]);
            return [];
        }

        $columns = Schema::getColumnListing($tableName);
        $excludedColumns = ['id', 'created_at', 'updated_at'];
        $availableColumns = array_diff($columns, $excludedColumns);

        $formattedColumns = [];

        foreach ($availableColumns as $col) {
            // Jika ada kolom foreign key (_id), ganti dengan nama relasi
            if (str_ends_with($col, '_id')) {
                $relationName = str_replace('_id', '', $col);
                $relationMethod = \Str::camel($relationName); // misalnya vehicleCategory

                if (method_exists($modelInstance, $relationMethod)) {
                    $formattedColumns[$col] = "{$relationMethod}.name"; // Jadi vehicleCategory.name
                } else {
                    $formattedColumns[$col] = $col; // Tetap ID jika tidak ada relasi
                }
            } else {
                $formattedColumns[$col] = $col;
            }
        }

        \Log::info('Available columns with relations', ['columns' => $formattedColumns]);

        return $formattedColumns;
    } catch (\Exception $e) {
        \Log::error('Error fetching table columns', ['error' => $e->getMessage()]);
        return [];
    }
}

}
