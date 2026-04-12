<?php

// ============================================
// MIGRATION: Tabel Ulasans (Reviews)
// Menyimpan ulasan/komentar pengguna terhadap film
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id();                              // ID unik ulasan
            $table->foreignId('user_id')               // ID user yang menulis ulasan
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('film_id')               // ID film yang diulas
                  ->constrained()
                  ->onDelete('cascade');
            $table->text('komentar');                   // Isi komentar/ulasan
            $table->timestamps();                      // created_at = tanggal ulasan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};
