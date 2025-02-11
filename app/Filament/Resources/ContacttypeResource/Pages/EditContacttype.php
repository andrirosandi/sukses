<?php

namespace App\Filament\Resources\ContacttypeResource\Pages;

use App\Filament\Resources\ContacttypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContacttype extends EditRecord
{
    protected static string $resource = ContacttypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
