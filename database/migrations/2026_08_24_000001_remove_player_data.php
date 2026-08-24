<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('player_stats_season');
        Schema::dropIfExists('player_stats_career');
        Schema::dropIfExists('player_team_history');
        Schema::dropIfExists('players');
    }

    public function down(): void
    {
        // Player data was intentionally removed and is not restored on rollback.
    }
};
