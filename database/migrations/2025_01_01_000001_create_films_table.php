<?php

// ============================================
// MIGRATION: Tabel Films
// Menyimpan data film yang ditampilkan di website
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();                              // ID unik film (auto increment)
            $table->string('judul', 200);              // Judul film
            $table->year('tahun');                      // Tahun rilis
            $table->string('genre', 100);              // Genre (Action, Drama, dll)
            $table->text('sinopsis');                   // Sinopsis / ringkasan cerita
            $table->string('poster')->nullable();      // Nama file poster yang diupload
            $table->timestamps();                      // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
