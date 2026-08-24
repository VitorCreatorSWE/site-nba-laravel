<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $teams = DB::table('teams')
            ->where('abbreviation', 'WAS')
            ->orderBy('id')
            ->get();

        if ($teams->count() < 2) {
            return;
        }

        $primaryId = $teams->first()->id;
        foreach ($teams->skip(1) as $duplicate) {
            DB::table('games')->where('home_team_id', $duplicate->id)->update(['home_team_id' => $primaryId]);
            DB::table('games')->where('away_team_id', $duplicate->id)->update(['away_team_id' => $primaryId]);
            DB::table('teams')->where('id', $duplicate->id)->delete();
        }
    }

    public function down(): void
    {
        // Duplicate team records are intentionally not restored.
    }
};
