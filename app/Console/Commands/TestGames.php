<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TestGames extends Command
{
    protected $signature = 'test:games {season=2025}';
    protected $description = 'Test fetching games from balldontlie API';

    public function handle()
    {
        $season = $this->argument('season');
        $svc = app(\App\Services\BalldontlieService::class);
        Cache::flush();

        $games = $svc->getGames(['seasons[]' => $season, 'per_page' => 5]);
        $this->info("Season {$season}: " . count($games) . " games");

        if (!empty($games)) {
            $g = $games[0];
            $this->info("First game: {$g['home_team']['full_name']} vs {$g['visitor_team']['full_name']}");
            $this->info("Date: {$g['date']}");
            $this->info("Status: {$g['status']}");
            $this->info("Score: {$g['home_team_score']} - {$g['visitor_team_score']}");
        }
    }
}
