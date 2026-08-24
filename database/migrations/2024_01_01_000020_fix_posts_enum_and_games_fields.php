<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('category', ['noticia', 'analise', 'opiniao', 'destaque', 'recente', 'estatistica'])->default('noticia')->change();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->string('season')->nullable()->after('away_score');
            $table->text('admin_analysis')->nullable()->after('highlights');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('category', ['noticia', 'analise', 'opiniao', 'destaque'])->default('noticia')->change();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['season', 'admin_analysis']);
        });
    }
};
