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

