<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear loan_histories table first (since it references books)
        DB::table('loan_histories')->truncate();
        
        // Run the seeders in the correct order
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            GenreSeeder::class,
            TypeSeeder::class,
            // BookSeeder::class,
        ]);
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

    }
}
