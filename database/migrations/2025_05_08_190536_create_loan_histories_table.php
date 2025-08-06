<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_histories', function (Blueprint $table) {
            $table->id('id_pinjaman');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_buku');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['pending', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('bukti_pinjam')->nullable();
            $table->string('bukti_kembali')->nullable();
            $table->decimal('denda', 10, 2)->default(0);
            $table->boolean('denda_dibayar')->default(false);
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_buku')->references('id_buku')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_histories');
    }
};
