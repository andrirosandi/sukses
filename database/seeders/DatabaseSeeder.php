<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ContactType;
use App\Models\VehicleType;
use App\Models\RecordCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ContactCategory;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         // Pastikan menggunakan guard web
         $guard = 'web';

         // Daftar permissions
         $permissions = [
             'permission.view', 'permission.create', 'permission.edit', 'permission.delete',
             'role.view', 'role.create', 'role.edit', 'role.delete',
             'user.view', 'user.create', 'user.edit', 'user.delete',
         ];
 
         // Buat permissions dengan guard
         foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
         }
 
         // Buat roles dengan guard
         $adminRole = Role::firstOrCreate(['name' => 'systemadministrator', 'guard_name' => $guard]);
         $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => $guard]);
 
         // Assign semua permission ke systemadministrator
         $adminRole->syncPermissions($permissions);
 
         // Buat user IT System Administrator
         $adminUser = User::updateOrCreate([
             'userid' => 'it',
         ], [
             'name' => 'IT System Administrator',
             'email' => 'suksesgroupit@gmail.com',
             'password' => Hash::make('megadethit'), // Ganti dengan password yang aman
         ]);
 
         // Assign role ke user
         $adminUser->assignRole($adminRole);
 

        //  
        RecordCategory::firstOrCreate(['name' => 'Vehicle', 'description' => 'Vehicle']);
        ContactCategory::firstOrCreate(['name' => 'Whatsapp', 'description' => 'Whatsapp']);
        ContactCategory::firstOrCreate(['name' => 'Email', 'description' => 'Email']);
        VehicleCategory::firstOrCreate(['name' => 'Car', 'description' => 'Car']);
        VehicleCategory::firstOrCreate(['name' => 'Truck', 'description' => 'Truck']);
        VehicleCategory::firstOrCreate(['name' => 'Motorcycle', 'description' => 'Motorcycle']);


         $this->command->info('Seeder berhasil dijalankan!');
    }
}
