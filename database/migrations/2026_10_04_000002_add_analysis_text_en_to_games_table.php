<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A analise gerada vive em uma coluna por idioma, assim como o PT
        // original. analysis_text continua sendo o portugues; esta e a
        // variante inglesa, preenchida sob demanda pelo GameAnalysis.
        Schema::table('games', function (Blueprint $table) {
            $table->text('analysis_text_en')->nullable()->after('analysis_text');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('analysis_text_en');
        });
    }
};