<?php

// ============================================
// MIGRATION: Tambah kolom nama untuk guest
// Rating & ulasan bisa tanpa login, cukup input nama
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            // user_id jadi nullable (guest tidak punya akun)
            $table->foreignId('user_id')->nullable()->change();
            // Tambah kolom nama untuk guest
            $table->string('nama', 100)->nullable()->after('film_id');
        });

        Schema::table('ulasans', function (Blueprint $table) {
            // user_id jadi nullable (guest tidak punya akun)
            $table->foreignId('user_id')->nullable()->change();
            // Tambah kolom nama untuk guest
            $table->string('nama', 100)->nullable()->after('film_id');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn('nama');
        });

        Schema::table('ulasans', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
};
