<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id('id_genre');
            $table->string('nama_genre');
            $table->unsignedBigInteger('id_kategori');
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('categories')
                  ->onDelete('cascade');
        });

        // Get category IDs
        $fiksiId = DB::table('categories')->where('nama_kategori', 'Fiksi')->value('id_kategori');
        $nonFiksiId = DB::table('categories')->where('nama_kategori', 'Non-Fiksi')->value('id_kategori');

        // Insert default genres for Fiksi
        DB::table('genres')->insert([
            ['nama_genre' => 'Romansa', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Fantasi', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Fiksi Ilmiah', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Misteri', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Horor', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Komedi', 'id_kategori' => $fiksiId],
            ['nama_genre' => 'Lainnya', 'id_kategori' => $fiksiId],
        ]);

        // Insert default genres for Non-Fiksi
        DB::table('genres')->insert([
            ['nama_genre' => 'Biografi', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Ensiklopedia', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Kamus', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Sains', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Filsafat', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Psikologi', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Sejarah', 'id_kategori' => $nonFiksiId],
            ['nama_genre' => 'Lainnya', 'id_kategori' => $nonFiksiId],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
