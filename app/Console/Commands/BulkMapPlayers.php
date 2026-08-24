<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Player;

class BulkMapPlayers extends Command
{
    protected $signature = 'nba:bulk-map-players';
    protected $description = 'Search balldontlie API for each unmapped player and get their real NBA ID';

    public function handle()
    {
        $apiKey = config('services.balldontlie.key');
        $unmapped = Player::whereNull('nba_api_id')->get();
        $this->info("Unmapped players: " . $unmapped->count());

        $matched = 0;
        $notFound = 0;

        foreach ($unmapped as $player) {
            $lastName = explode(' ', $player->full_name);
            $search = end($lastName);

            try {
                $response = Http::withoutVerifying()
                    ->withHeaders(['Authorization' => $apiKey])
                    ->timeout(5)
                    ->get('https://api.balldontlie.io/v1/players', [
                        'search' => $player->full_name,
                        'per_page' => 5,
                    ]);

                if ($response->successful()) {
                    $results = $response->json('data', []);
                    foreach ($results as $r) {
                        $apiName = trim($r['first_name'] . ' ' . $r['last_name']);
                        if (levenshtein(strtolower($apiName), strtolower($player->full_name)) <= 2) {
                            $player->update(['nba_api_id' => $r['id']]);
                            $matched++;
                            $this->info("  {$player->full_name} -> {$r['id']}");
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                $notFound++;
            }

            usleep(100000);
        }

        $this->info("Matched: {$matched}");
        $this->info("Not found: {$notFound}");
        $this->info("Players with NBA ID: " . Player::whereNotNull('nba_api_id')->count());
    }
}
