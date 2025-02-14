<?php

namespace App\Filament\Resources\MessageProviderResource\Pages;

use App\Filament\Resources\MessageProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMessageProviders extends ListRecords
{
    protected static string $resource = MessageProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
