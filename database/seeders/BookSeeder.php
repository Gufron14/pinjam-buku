<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Categories;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks before truncating
        Schema::disableForeignKeyConstraints();
        
        // Clear existing books
        DB::table('books')->truncate();
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Get category IDs
        $fiksiId = Categories::where('nama_kategori', 'Fiksi')->first()->id_kategori;
        $nonFiksiId = Categories::where('nama_kategori', 'Non-Fiksi')->first()->id_kategori;

        // Rest of your code remains the same...
        // Get genre IDs
        $romansaId = Genre::where('nama_genre', 'Romansa')->first()->id_genre;
        $fantasiId = Genre::where('nama_genre', 'Fantasi')->first()->id_genre;
        $fiksiIlmiahId = Genre::where('nama_genre', 'Fiksi Ilmiah')->first()->id_genre;
        $misteriId = Genre::where('nama_genre', 'Misteri')->first()->id_genre;
        $hororId = Genre::where('nama_genre', 'Horor')->first()->id_genre;
        $komediId = Genre::where('nama_genre', 'Komedi')->first()->id_genre;
        $fiksiLainnyaId = Genre::where('nama_genre', 'Lainnya')->where('id_kategori', $fiksiId)->first()->id_genre;
        
        $biografiId = Genre::where('nama_genre', 'Biografi')->first()->id_genre;
        $sainsId = Genre::where('nama_genre', 'Sains')->first()->id_genre;
        $filsafatId = Genre::where('nama_genre', 'Filsafat')->first()->id_genre;
        $psikologiId = Genre::where('nama_genre', 'Psikologi')->first()->id_genre;
        $sejarahId = Genre::where('nama_genre', 'Sejarah')->first()->id_genre;
        $nonFiksiLainnyaId = Genre::where('nama_genre', 'Lainnya')->where('id_kategori', $nonFiksiId)->first()->id_genre;

        // Get type IDs
        $novelId = Type::where('nama_jenis', 'Novel')->first()->id_jenis;
        $cerpenId = Type::where('nama_jenis', 'Cerpen')->first()->id_jenis;
        $komikId = Type::where('nama_jenis', 'Komik')->first()->id_jenis;
        $bukuAnakId = Type::where('nama_jenis', 'Buku Anak-anak')->first()->id_jenis;
        $lainnyaId = Type::where('nama_jenis', 'Lainnya')->first()->id_jenis;

        // List of 20 famous Indonesian books
        // List of 20 famous Indonesian books
        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2005,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1980,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Ayat-Ayat Cinta',
                'penulis' => 'Habiburrahman El Shirazy',
                'tahun_terbit' => 2004,
                'id_kategori' => $fiksiId,
                'id_genre' => $romansaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Ronggeng Dukuh Paruk',
                'penulis' => 'Ahmad Tohari',
                'tahun_terbit' => 1982,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Dilan 1990',
                'penulis' => 'Pidi Baiq',
                'tahun_terbit' => 2014,
                'id_kategori' => $fiksiId,
                'id_genre' => $romansaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Filosofi Kopi',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2006,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $cerpenId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Sang Pemimpi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2006,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2009,
                'id_kategori' => $fiksiId,
                'id_genre' => $romansaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Tenggelamnya Kapal Van Der Wijck',
                'penulis' => 'Hamka',
                'tahun_terbit' => 1938,
                'id_kategori' => $fiksiId,
                'id_genre' => $romansaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'tahun_terbit' => 2002,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Pulang',
                'penulis' => 'Leila S. Chudori',
                'tahun_terbit' => 2012,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Saman',
                'penulis' => 'Ayu Utami',
                'tahun_terbit' => 1998,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Lelaki Harimau',
                'penulis' => 'Eka Kurniawan',
                'tahun_terbit' => 2004,
                'id_kategori' => $fiksiId,
                'id_genre' => $fantasiId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Supernova: Kesatria, Putri, dan Bintang Jatuh',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2001,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiIlmiahId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Laut Bercerita',
                'penulis' => 'Leila S. Chudori',
                'tahun_terbit' => 2017,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Gadis Pantai',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1962,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Sepotong Senja untuk Pacarku',
                'penulis' => 'Seno Gumira Ajidarma',
                'tahun_terbit' => 2002,
                'id_kategori' => $fiksiId,
                'id_genre' => $romansaId,
                'id_jenis' => $cerpenId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Manusia Setengah Salmon',
                'penulis' => 'Raditya Dika',
                'tahun_terbit' => 2011,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $nonFiksiLainnyaId,
                'id_jenis' => $lainnyaId,
                'stok' => rand(1, 10)
            ],
            [
                'judul' => 'Jejak Langkah',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1985,
                'id_kategori' => $fiksiId,
                'id_genre' => $fiksiLainnyaId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10)
            ],
        ];

        // Insert books into database
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
