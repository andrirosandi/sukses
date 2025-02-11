<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika password kosong, hapus dari inputan agar tidak diupdate
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Jika ada input password, hash sebelum menyimpan
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }
    
    protected function afterCreate(): void 
    {
        $roleId = $this->data['role_id'];
        if (!empty($roleId)) {
            $roleName = Role::find($roleId[0])?->name;
            if ($roleName) {
                $this->record->syncRoles([$roleName]);
            }
        }
    }
}
