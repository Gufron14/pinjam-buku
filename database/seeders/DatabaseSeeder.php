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
            BookSeeder::class,
        ]);
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        
        // Now seed the loan histories
        $this->seedLoanHistories();
    }
    
    /**
     * Seed loan histories after all other tables are seeded
     */
    private function seedLoanHistories(): void
    {   

        // Get a user (user with ID 2 from UserSeeder)
        $userId = 2; // This is the regular user (Egi Hamdani)
        
        // Get some books
        $books = \App\Models\Book::take(3)->get();
        
        if ($books->count() < 3) {
            $this->command->info('Not enough books in the database. Please check your book seeder.');
            return;
        }
        
        // Create 3 loan histories for the user
        // 1. A book that is currently borrowed
        \App\Models\LoanHistory::create([
            'id_user' => $userId,
            'id_buku' => $books[0]->id_buku,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'dipinjam',
        ]);
        
        // 2. A book that has been returned
        \App\Models\LoanHistory::create([
            'id_user' => $userId,
            'id_buku' => $books[1]->id_buku,
            'tanggal_pinjam' => now()->subDays(15),
            'tanggal_kembali' => now()->subDays(8),
            'status' => 'dikembalikan',
        ]);
        
        // 3. A book that is overdue
        \App\Models\LoanHistory::create([
            'id_user' => $userId,
            'id_buku' => $books[2]->id_buku,
            'tanggal_pinjam' => now()->subDays(20),
            'tanggal_kembali' => now()->subDays(5),
            'status' => 'terlambat',
        ]);
    }
}
