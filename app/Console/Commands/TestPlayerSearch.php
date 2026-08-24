<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BalldontlieService;

class TestPlayerSearch extends Command
{
    protected $signature = 'test:player-search {name}';
    protected $description = 'Test player search from balldontlie API';

    public function handle()
    {
        $name = $this->argument('name');
        $svc = app(BalldontlieService::class);
        $players = $svc->getPlayers(['search' => $name, 'per_page' => 3]);

        foreach ($players as $p) {
            $this->info("ID: {$p['id']} | {$p['first_name']} {$p['last_name']} | Team: " . ($p['team']['full_name'] ?? 'N/A'));
        }

        if (empty($players)) {
            $this->info('No players found');
        }
    }
}
