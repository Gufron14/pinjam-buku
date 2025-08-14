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

        // Get category IDs (harus sudah ada dari BookStructureSeeder)
        $fiksiId = Category::where('nama_kategori', 'Fiksi')->firstOrFail()->id_kategori;
        $nonFiksiId = Category::where('nama_kategori', 'Non Fiksi')->firstOrFail()->id_kategori;

        // Get type IDs (harus sudah ada dari BookStructureSeeder)
        $novelId = Type::where('nama_jenis', 'Novel')->first()?->id_jenis;
        $cerpenId = Type::where('nama_jenis', 'Cerpen')->first()?->id_jenis;
        $komikId = Type::where('nama_jenis', 'Komik')->first()?->id_jenis;
        $biografiId = Type::where('nama_jenis', 'Biografi')->first()?->id_jenis;
        $ensiklopediaId = Type::where('nama_jenis', 'Ensiklopedia')->first()?->id_jenis;
        $bukuAnakId = Type::where('nama_jenis', 'Buku Anak-anak')->first()?->id_jenis;

        // Get genre IDs (harus sudah ada dari BookStructureSeeder)
        $fantasiId = Genre::where('nama_genre', 'Fantasi')->first()?->id_genre;
        $misteriId = Genre::where('nama_genre', 'Misteri')->first()?->id_genre;
        $romantisId = Genre::where('nama_genre', 'Romantis')->first()?->id_genre;
        $petualanganId = Genre::where('nama_genre', 'Petualangan')->first()?->id_genre;
        $humorId = Genre::where('nama_genre', 'Humor')->first()?->id_genre;
        $inspiratifId = Genre::where('nama_genre', 'Inspiratif')->first()?->id_genre;
        $dramaId = Genre::where('nama_genre', 'Drama')->first()?->id_genre;
        $tokohId = Genre::where('nama_genre', 'Tokoh')->first()?->id_genre;
        $sejarahId = Genre::where('nama_genre', 'Sejarah')->first()?->id_genre;
        $edukasiId = Genre::where('nama_genre', 'Edukasi')->first()?->id_genre;
        $sainsId = Genre::where('nama_genre', 'Sains')->first()?->id_genre;
        $motivasiId = Genre::where('nama_genre', 'Motivasi')->first()?->id_genre;

        // Fallback untuk genre jika tidak ada, gunakan yang pertama ditemukan
        $fallbackGenreId = Genre::first()?->id_genre ?? 1;

        // List of books dengan genre yang sesuai dengan BookStructureSeeder
        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2005,
                'id_kategori' => $fiksiId,
                'id_genre' => $inspiratifId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 13
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1980,
                'id_kategori' => $fiksiId,
                'id_genre' => $sejarahId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 17
            ],
            [
                'judul' => 'Ayat-Ayat Cinta',
                'penulis' => 'Habiburrahman El Shirazy',
                'tahun_terbit' => 2004,
                'id_kategori' => $fiksiId,
                'id_genre' => $romantisId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 17
            ],
            [
                'judul' => 'Ronggeng Dukuh Paruk',
                'penulis' => 'Ahmad Tohari',
                'tahun_terbit' => 1982,
                'id_kategori' => $fiksiId,
                'id_genre' => $dramaId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 17
            ],
            [
                'judul' => 'Dilan: Dia adalah Dilanku Tahun 1990',
                'penulis' => 'Pidi Baiq',
                'tahun_terbit' => 2014,
                'id_kategori' => $fiksiId,
                'id_genre' => $romantisId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 15
            ],
            [
                'judul' => 'Petualangan Sherina',
                'penulis' => 'Mira Lesmana',
                'tahun_terbit' => 2000,
                'id_kategori' => $fiksiId,
                'id_genre' => $petualanganId ?? $fallbackGenreId,
                'id_jenis' => $cerpenId,
                'stok' => rand(1, 10),
                'untuk_umur' => 10
            ],
            [
                'judul' => 'Sang Pemimpi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2006,
                'id_kategori' => $fiksiId,
                'id_genre' => $inspiratifId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 13
            ],
            [
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2009,
                'id_kategori' => $fiksiId,
                'id_genre' => $romantisId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 16
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
                'id_kategori' => $fiksiId,
                'id_genre' => $inspiratifId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 15
            ],
            [
                'judul' => 'Tenggelamnya Kapal Van Der Wijck',
                'penulis' => 'Hamka',
                'tahun_terbit' => 1938,
                'id_kategori' => $fiksiId,
                'id_genre' => $romantisId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 17
            ],
            [
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'tahun_terbit' => 2002,
                'id_kategori' => $fiksiId,
                'id_genre' => $dramaId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 18
            ],
            [
                'judul' => 'Lelaki Harimau',
                'penulis' => 'Eka Kurniawan',
                'tahun_terbit' => 2004,
                'id_kategori' => $fiksiId,
                'id_genre' => $fantasiId ?? $fallbackGenreId,
                'id_jenis' => $novelId,
                'stok' => rand(1, 10),
                'untuk_umur' => 17
            ],
            [
                'judul' => 'Si Juki: Komik Strip',
                'penulis' => 'Faza Meonk',
                'tahun_terbit' => 2015,
                'id_kategori' => $fiksiId,
                'id_genre' => $humorId ?? $fallbackGenreId,
                'id_jenis' => $komikId,
                'stok' => rand(1, 10),
                'untuk_umur' => 13
            ],
            [
                'judul' => 'Detektif Conan: Misteri Pembunuhan',
                'penulis' => 'Gosho Aoyama',
                'tahun_terbit' => 2010,
                'id_kategori' => $fiksiId,
                'id_genre' => $misteriId ?? $fallbackGenreId,
                'id_jenis' => $komikId,
                'stok' => rand(1, 10),
                'untuk_umur' => 12
            ],
            [
                'judul' => 'Habibie & Ainun',
                'penulis' => 'Bacharuddin Jusuf Habibie',
                'tahun_terbit' => 2010,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $tokohId ?? $fallbackGenreId,
                'id_jenis' => $biografiId,
                'stok' => rand(1, 10),
                'untuk_umur' => 16
            ],
            [
                'judul' => 'Soekarno: Biografi Sang Proklamator',
                'penulis' => 'Cindy Adams',
                'tahun_terbit' => 1966,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $sejarahId ?? $fallbackGenreId,
                'id_jenis' => $biografiId,
                'stok' => rand(1, 10),
                'untuk_umur' => 15
            ],
            [
                'judul' => 'Ensiklopedia Anak: Dunia Sains',
                'penulis' => 'Tim Penerbit',
                'tahun_terbit' => 2018,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $sainsId ?? $fallbackGenreId,
                'id_jenis' => $ensiklopediaId,
                'stok' => rand(1, 10),
                'untuk_umur' => 10
            ],
            [
                'judul' => 'Buku Cerita Anak: Petualangan Si Kancil',
                'penulis' => 'Penulis Anonim',
                'tahun_terbit' => 2020,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $edukasiId ?? $fallbackGenreId,
                'id_jenis' => $bukuAnakId,
                'stok' => rand(1, 10),
                'untuk_umur' => 6
            ],
            [
                'judul' => 'Motivasi untuk Anak: Meraih Mimpi',
                'penulis' => 'Merry Riana',
                'tahun_terbit' => 2019,
                'id_kategori' => $nonFiksiId,
                'id_genre' => $motivasiId ?? $fallbackGenreId,
                'id_jenis' => $bukuAnakId,
                'stok' => rand(1, 10),
                'untuk_umur' => 8
            ],
        ];

        // Insert books into database
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
