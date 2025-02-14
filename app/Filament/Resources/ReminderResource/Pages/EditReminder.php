<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use Filament\Actions;
use App\Models\Reminder;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ReminderResource;

class EditReminder extends EditRecord
{
    protected static string $resource = ReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void 
    {
        dump($this->data['recipient']);
        $id = $this->record->id;
        $contactIds = $this->data['recipient'] ?? [];

        if (!empty($contactIds)) {
            $this->record->contacts()->sync($contactIds);
        }
        
    }

}
