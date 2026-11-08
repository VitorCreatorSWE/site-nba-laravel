<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gerado uma unica vez e relido depois. Precisa ser separado de
        // games.admin_analysis, que e o texto digitado manualmente pelo admin.
        Schema::table('games', function (Blueprint $table) {
            $table->text('analysis_text')->nullable()->after('admin_analysis');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('analysis_text');
        });
    }
};