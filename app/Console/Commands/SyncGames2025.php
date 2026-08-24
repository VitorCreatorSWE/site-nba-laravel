<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Team;
use App\Models\Game;

class SyncGames2025 extends Command
{
    protected $signature = 'nba:sync-2025';
    protected $description = 'Sync 2025-2026 season games from API with rate limit handling';

    public function handle()
    {
        $apiKey = config('services.balldontlie.key');
        $count = 0;
        $retries = 0;
        $maxRetries = 3;

        for ($page = 1; $page <= 30; $page++) {
            $response = Http::withoutVerifying()
                ->withHeaders(['Authorization' => $apiKey])
                ->timeout(15)
                ->get('https://api.balldontlie.io/v1/games', [
                    'seasons[]' => 2025,
                    'per_page' => 100,
                    'page' => $page,
                ]);

            if ($response->status() === 429) {
                $retries++;
                if ($retries >= $maxRetries) {
                    $this->error("Rate limited {$maxRetries} times, stopping");
                    break;
                }
                $this->warn("Rate limited, waiting 60s (retry {$retries}/{$maxRetries})...");
                sleep(60);
                $page--;
                continue;
            }

            if (!$response->successful()) {
                $this->error("API error: " . $response->status());
                break;
            }

            $games = $response->json('data', []);
            $this->info("Page {$page}: " . count($games) . " games");

            if (empty($games)) break;

            $newCount = 0;
            foreach ($games as $g) {
                $home = Team::where('api_id', $g['home_team']['id'] ?? 0)->first();
                $away = Team::where('api_id', $g['visitor_team']['id'] ?? 0)->first();
                if (!$home || !$away) continue;

                $existing = Game::where('api_id', $g['id'])->first();
                if (!$existing) {
                    Game::create([
                        'api_id' => $g['id'],
                        'home_team_id' => $home->id,
                        'away_team_id' => $away->id,
                        'game_date' => $g['date'],
                        'status' => $g['status'] === 'Final' ? 'finished' : 'scheduled',
                        'home_score' => $g['home_team_score'] ?? 0,
                        'away_score' => $g['visitor_team_score'] ?? 0,
                        'season' => '2025',
                    ]);
                    $newCount++;
                    $count++;
                }
            }

            $this->info("  {$newCount} new games");

            sleep(3);
        }

        $this->info("New 2025 games synced: {$count}");
        $this->info("Total 2025 games: " . Game::where('season', '2025')->count());
        $this->info("Total games in DB: " . Game::count());
    }
}
