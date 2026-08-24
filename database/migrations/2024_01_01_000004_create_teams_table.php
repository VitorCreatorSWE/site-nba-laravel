<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->integer('api_id')->unique();
            $table->string('name');
            $table->string('abbreviation', 10);
            $table->string('city');
            $table->string('state')->nullable();
            $table->enum('conference', ['East', 'West']);
            $table->string('division');
            $table->string('arena_name')->nullable();
            $table->integer('arena_capacity')->nullable();
            $table->string('arena_location')->nullable();
            $table->integer('founded_year')->nullable();
            $table->integer('championships_count')->default(0);
            $table->string('logo_url')->nullable();
            $table->timestamps();

            $table->index('abbreviation');
            $table->index('conference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};