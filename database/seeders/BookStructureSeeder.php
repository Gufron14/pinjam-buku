<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BookStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks before truncating
        Schema::disableForeignKeyConstraints();
        
        // Truncate tables first to avoid duplicates (in reverse dependency order)
        DB::table('genres')->truncate();
        DB::table('types')->truncate();
        DB::table('categories')->truncate();
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $kategori = [
            'Fiksi' => [
                'Novel' => ['Fantasi', 'Misteri', 'Romantis'],
                'Komik' => ['Petualangan', 'Humor'],
                'Cerpen' => ['Inspiratif', 'Drama'],
            ],
            'Non Fiksi' => [
                'Biografi' => ['Tokoh', 'Sejarah'],
                'Ensiklopedia' => ['Edukasi', 'Sains'],
                'Buku Anak-anak' => ['Edukasi', 'Motivasi'],
            ],
        ];

        // Insert kategori
        $kategoriIds = [];
        foreach (array_keys($kategori) as $namaKategori) {
            $kategoriIds[$namaKategori] = DB::table('categories')->insertGetId([
                'nama_kategori' => $namaKategori
            ]);
        }

        // Insert jenis dan genre
        foreach ($kategori as $namaKategori => $jenisArr) {
            foreach ($jenisArr as $namaJenis => $genreArr) {
                $jenisId = DB::table('types')->insertGetId([
                    'nama_jenis' => $namaJenis,
                    'id_kategori' => $kategoriIds[$namaKategori]
                ]);
                foreach ($genreArr as $namaGenre) {
                    DB::table('genres')->insert([
                        'nama_genre' => $namaGenre,
                        'id_jenis' => $jenisId
                    ]);
                }
            }
        }
    }
}