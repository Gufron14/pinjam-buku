<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for book management
        $permissions = [
            // Book permissions
            'view books',
            'create books',
            'edit books',
            'delete books',
            
            // Borrowing permissions
            'approve borrowing',
            'reject borrowing',
            'view borrowing history',
            'borrow books',
            'return books',
            
            // Member permissions
            'view members',
            'create members',
            'edit members',
            'delete members',
            
            // Report permissions
            'view reports',
            'generate reports',
            
            // Settings permissions
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Admin Role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create User Role with limited permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view books',
            'borrow books',
            'return books',
            'view borrowing history'
        ]);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin Taman Baca',
            'email' => 'admin@tamanbaca.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Regular User
        $user = User::create([
            'name' => 'Egi Hamdani',
            'email' => 'egi@gmail.com',
            'password' => Hash::make('password'),
            'alamat' => 'Cicalengka, Kab. Bandung, Jawa Barat',
            'no_telepon' => '08123456789',
            'jenis_kelamin' => 'Laki-laki',
        ]);
        $user->assignRole('user');
    }
}

