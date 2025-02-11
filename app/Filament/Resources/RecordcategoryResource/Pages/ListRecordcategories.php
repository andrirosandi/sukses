<?php

namespace App\Filament\Resources\RecordcategoryResource\Pages;

use App\Filament\Resources\RecordcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecordcategories extends ListRecords
{
    protected static string $resource = RecordcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
