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
        Schema::create('types', function (Blueprint $table) {
            $table->id('id_jenis');
            $table->string('nama_jenis');
            $table->timestamps();
        });

        // Insert default book types
        DB::table('types')->insert([
            ['nama_jenis' => 'Novel'],
            ['nama_jenis' => 'Cerpen'],
            ['nama_jenis' => 'Komik'],
            ['nama_jenis' => 'Buku Anak-anak'],
            ['nama_jenis' => 'Lainnya'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
