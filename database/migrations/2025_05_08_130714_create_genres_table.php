<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id('id_genre');
            $table->string('nama_genre');
            $table->unsignedBigInteger('id_jenis'); // relasi ke types
            $table->timestamps();

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('types')
                ->onDelete('cascade');
            $table->unique(['nama_genre', 'id_jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};