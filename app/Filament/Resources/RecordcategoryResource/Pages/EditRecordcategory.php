<?php

namespace App\Filament\Resources\RecordcategoryResource\Pages;

use App\Filament\Resources\RecordcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecordcategory extends EditRecord
{
    protected static string $resource = RecordcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
