<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->bigInteger('nba_api_id')->nullable()->after('api_id');
        });

        Schema::table('players', function (Blueprint $table) {
            $table->bigInteger('nba_api_id')->nullable()->after('api_id');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('nba_api_id');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('nba_api_id');
        });
    }
};
