<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('genres')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get category IDs
        $fiksiId = Categories::where('nama_kategori', 'Fiksi')->first()->id_kategori;
        $nonFiksiId = Categories::where('nama_kategori', 'Non-Fiksi')->first()->id_kategori;

        $genres = [
            // Fiksi genres
            ['nama_genre' => 'Romansa', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Fantasi', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Fiksi Ilmiah', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Misteri', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Horor', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Komedi', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Lainnya', 'id_kategori' => $fiksiId],
            
            // Non-Fiksi genres
            ['nama_genre' => 'Biografi', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Sains', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Filsafat', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Psikologi', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Sejarah', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Lainnya', 'id_kategori' => $nonFiksiId],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
