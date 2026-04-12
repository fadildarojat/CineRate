<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('film_id')->nullable()->change();
            $table->unsignedBigInteger('tmdb_id')->nullable()->after('film_id');
            $table->index('tmdb_id');
        });

        Schema::table('ulasans', function (Blueprint $table) {
            $table->foreignId('film_id')->nullable()->change();
            $table->unsignedBigInteger('tmdb_id')->nullable()->after('film_id');
            $table->index('tmdb_id');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex(['tmdb_id']);
            $table->dropColumn('tmdb_id');
        });

        Schema::table('ulasans', function (Blueprint $table) {
            $table->dropIndex(['tmdb_id']);
            $table->dropColumn('tmdb_id');
        });
    }
};
