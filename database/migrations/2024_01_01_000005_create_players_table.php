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
            $table->integer('api_id')->unique();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->string('position');
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('wingspan_cm', 5, 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('jersey_number', 5)->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();

            $table->index('full_name');
            $table->index('team_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};