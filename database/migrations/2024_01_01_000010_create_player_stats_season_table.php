<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_stats_season', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->string('season', 9);
            $table->integer('games_played')->default(0);
            $table->decimal('points_per_game', 5, 2)->default(0);
            $table->decimal('rebounds_per_game', 5, 2)->default(0);
            $table->decimal('assists_per_game', 5, 2)->default(0);
            $table->decimal('field_goal_percentage', 5, 2)->default(0);
            $table->decimal('three_point_percentage', 5, 2)->default(0);
            $table->decimal('free_throw_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['player_id', 'season']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_stats_season');
    }
};