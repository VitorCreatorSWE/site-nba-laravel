<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Player;

class MapPlayerIds extends Command
{
    protected $signature = 'nba:map-player-ids';
    protected $description = 'Map all player names to NBA CDN headshot IDs';

    public function handle()
    {
        $this->info('Fetching NBA player data from balldontlie...');

        $balldontlie = app(\App\Services\BalldontlieService::class);

        $playerMap = [];
        $page = 1;

        while ($page <= 50) {
            $players = $balldontlie->getPlayers(['per_page' => 100, 'page' => $page]);
            if (empty($players)) break;

            foreach ($players as $p) {
                $fullName = trim($p['first_name'] . ' ' . $p['last_name']);
                if (!isset($playerMap[$fullName])) {
                    $playerMap[$fullName] = $p['id'];
                }
            }

            $this->info("  Page {$page}: " . count($players) . " players");
            $page++;
        }

        $this->info("Total unique names from API: " . count($playerMap));

        $matched = 0;
        $notFound = 0;

        foreach (Player::all() as $player) {
            $name = $player->full_name;
            if (isset($playerMap[$name])) {
                $player->update(['nba_api_id' => $playerMap[$name]]);
                $matched++;
            } else {
                $notFound++;
            }
        }

        $this->info("Matched: {$matched}");
        $this->info("Not found: {$notFound}");
        $this->info("Players with NBA ID: " . Player::whereNotNull('nba_api_id')->count());
    }
}
