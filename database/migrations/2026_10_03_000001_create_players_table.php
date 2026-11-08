<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->integer('api_id')->nullable()->unique();
            $table->integer('apisports_id')->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('slug', 191)->unique();
            $table->string('position', 10)->nullable();
            $table->string('jersey_number', 10)->nullable();
            $table->string('height', 10)->nullable();
            $table->decimal('height_meters', 4, 2)->nullable();
            $table->integer('weight')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('country', 80)->nullable();
            $table->string('college')->nullable();
            $table->smallInteger('draft_year')->nullable();
            $table->smallInteger('draft_round')->nullable();
            $table->smallInteger('draft_number')->nullable();
            $table->smallInteger('career_start')->nullable();
            $table->smallInteger('seasons_played')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('season', 10)->nullable();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamps();

            $table->index('team_id');
            $table->index('position');
            $table->index('last_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
