<?php

// ============================================
// MIGRATION: Tabel Ratings
// Menyimpan rating 1-5 bintang dari pengguna
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();                              // ID unik rating
            $table->foreignId('user_id')               // ID user yang memberi rating
                  ->constrained()                      // Foreign key ke tabel users
                  ->onDelete('cascade');                // Hapus rating jika user dihapus
            $table->foreignId('film_id')               // ID film yang diberi rating
                  ->constrained()                      // Foreign key ke tabel films
                  ->onDelete('cascade');                // Hapus rating jika film dihapus
            $table->tinyInteger('rating');              // Nilai rating (1-5)
            $table->timestamps();

            // Satu user hanya bisa memberi 1 rating per film
            $table->unique(['user_id', 'film_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
