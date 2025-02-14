<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use Filament\Actions;
use App\Models\Contact;
use App\Models\Reminder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReminderResource;

class CreateReminder extends CreateRecord
{
    protected static string $resource = ReminderResource::class;
    // ->afterStateUpdated(function ($state, $set, $record) {
    //     if ($record) {
    //         // Hapus semua data lama untuk reminder ini
    //         ReminderRecipient::where('reminder_id', $record->id)->delete();

    //         // Simpan data baru ke pivot
    //         foreach ($state as $contactId) {
    //             ReminderRecipient::create([
    //                 'reminder_id' => $record->id,
    //                 'contact_id' => $contactId,
    //             ]);
    //         }
    //     }
    // })
    protected function afterCreate(): void 
    {
        $id = $this->record->id;
        $contactIds = $this->data['recipient'] ?? [];

        if (!empty($contactIds)) {
            $this->record->contacts()->sync($contactIds);
        }
    }
}
