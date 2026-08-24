<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('teams')
            ->where('abbreviation', 'WAS')
            ->update([
                'name' => 'Washington Wizards',
                'conference' => 'East',
                'division' => 'Southeast',
            ]);
    }

    public function down(): void
    {
        // Keep the corrected official team data on rollback.
    }
};
