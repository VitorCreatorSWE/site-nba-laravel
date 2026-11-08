<?php

namespace App\Console\Commands;

use App\Services\GameAnalysis;
use Illuminate\Console\Command;

class GenerateGameAnalysis extends Command
{
    protected $signature = 'nba:generate-analysis
                            {--locale=* : Idiomas a preencher (padrao: todos)}';

    protected $description = 'Gera e persiste o texto de analise dos jogos finalizados que ainda nao tem';

    public function handle(GameAnalysis $analysis): int
    {
        $locales = $this->option('locale') ?: array_keys(GameAnalysis::COLUMNS);

        $invalid = array_diff($locales, array_keys(GameAnalysis::COLUMNS));

        if ($invalid) {
            $this->error('Locale invalido: '.implode(', ', $invalid));
            $this->line('Validos: '.implode(', ', array_keys(GameAnalysis::COLUMNS)));

            return self::FAILURE;
        }

        $this->info('Gerando analise ('.implode(', ', $locales).') para os jogos finalizados sem texto salvo...');

        $count = $analysis->persistMissing(function ($game) {
            $this->line(sprintf(
                '  #%d %s - %s',
                $game->id,
                $game->homeTeam?->abbreviation ?? '???',
                $game->awayTeam?->abbreviation ?? '???'
            ));
        }, $locales);

        $this->newLine();
        $this->info("Analises geradas: {$count}");
        $this->line('Jogos que ja tinham texto salvo nao foram tocados.');

        return self::SUCCESS;
    }
}