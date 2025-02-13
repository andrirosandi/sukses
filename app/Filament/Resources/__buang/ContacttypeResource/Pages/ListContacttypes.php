<?php

namespace App\Filament\Resources\ContacttypeResource\Pages;

use App\Filament\Resources\ContacttypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContacttypes extends ListRecords
{
    protected static string $resource = ContacttypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
