<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id('id_buku');
            $table->string('judul');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_genre');
            $table->unsignedBigInteger('id_jenis');
            $table->integer('stok')->default(0);
            $table->string('penulis')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('categories')
                  ->onDelete('cascade');
                  
            $table->foreign('id_genre')
                  ->references('id_genre')
                  ->on('genres')
                  ->onDelete('cascade');
                  
            $table->foreign('id_jenis')
                  ->references('id_jenis')
                  ->on('types')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
