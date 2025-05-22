<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the permissions cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Truncate the permissions table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Book permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'view books']);
        Permission::create(['name' => 'create books']);
        Permission::create(['name' => 'edit books']);
        Permission::create(['name' => 'delete books']);
        
        // Loan permissions
        Permission::create(['name' => 'create loans']);
        Permission::create(['name' => 'view loans']);
        Permission::create(['name' => 'approve loans']);
        Permission::create(['name' => 'reject loans']);
        Permission::create(['name' => 'process returns']);
        
        // User management permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        
        // Report permissions
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'export reports']);
    }
}
