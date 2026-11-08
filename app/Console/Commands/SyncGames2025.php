<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncGames2025 extends Command
{
    protected $signature = 'nba:sync-2025 {--fresh : Limpa o cache antes de sincronizar}';

    protected $description = 'Sincroniza os jogos da temporada corrente pela balldontlie (alias do nba:sync)';

    public function handle(): int
    {
        $arguments = ['--only=games', '--source=balldontlie'];

        if ($this->option('fresh')) {
            $arguments[] = '--fresh';
        }

        return $this->call('nba:sync', $arguments);
    }
}
