<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\LoanHistory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user (assuming you have users in your database)
        // $user = User::first();
        
        // if (!$user) {
        //     // Create a user if none exists
        //     $user = User::create([
        //         'name' => 'Test User',
        //         'email' => 'test@example.com',
        //         'password' => bcrypt('password'),
        //     ]);
        // }
        
        // Get some books (assuming you have books in your database)
        $books = Book::take(3)->get();
        
        if ($books->count() < 3) {
            $this->command->info('Not enough books in the database. Please seed books first.');
            return;
        }
        
        // Create 3 loan histories for the user
        // 1. A book that is currently borrowed
        LoanHistory::create([
            'id_user' => 2,
            'id_buku' => $books[0]->id_buku,
            'tanggal_pinjam' => Carbon::now()->subDays(5),
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'status' => 'dipinjam',
        ]);
        
        // 2. A book that has been returned
        LoanHistory::create([
            'id_user' => 2,
            'id_buku' => $books[1]->id_buku,
            'tanggal_pinjam' => Carbon::now()->subDays(15),
            'tanggal_kembali' => Carbon::now()->subDays(8),
            'status' => 'dikembalikan',
        ]);
        
        // 3. A book that is overdue
        LoanHistory::create([
            'id_user' => 2,
            'id_buku' => $books[2]->id_buku,
            'tanggal_pinjam' => Carbon::now()->subDays(20),
            'tanggal_kembali' => Carbon::now()->subDays(5),
            'status' => 'terlambat',
        ]);
    }
}
