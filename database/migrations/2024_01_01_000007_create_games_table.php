<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('api_id')->unique()->nullable();
            $table->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
            $table->dateTime('game_date');
            $table->string('arena')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->json('highlights')->nullable();
            $table->timestamps();

            $table->index('game_date');
            $table->index('status');
            $table->index(['home_team_id', 'away_team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};