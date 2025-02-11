<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
             'permission.view', 'permission.insert', 'permission.update', 'permission.delete',
             'role.view', 'role.insert', 'role.update', 'role.delete',
             'user.view', 'user.insert', 'user.update', 'user.delete',
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
 
         $this->command->info('Seeder berhasil dijalankan!');
    }
}
