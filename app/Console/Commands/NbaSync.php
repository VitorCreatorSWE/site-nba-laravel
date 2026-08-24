<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\BalldontlieService;
use App\Models\Team;
use App\Models\Game;

class NbaSync extends Command
{
    protected $signature = 'nba:sync {--only= : teams,games}';
    protected $description = 'Sync NBA data from balldontlie API';

    public function handle()
    {
        $only = $this->option('only');
        $balldontlie = app(BalldontlieService::class);

        Cache::flush();

        if (!$only || $only === 'teams') {
            $this->info('Sincronizando times...');
            $balldontlie->syncTeams();
            $this->info('Times no banco: ' . Team::count());
        }

        if (!$only || $only === 'games') {
            $this->info('Sincronizando jogos...');
            $count = 0;
            foreach ([2023, 2024, 2025] as $season) {
                $page = 1;
                while ($page <= 5) {
                    Cache::flush();
                    $games = $balldontlie->getGames([
                        'seasons[]' => $season,
                        'per_page' => 100,
                        'page' => $page,
                    ]);
                    $this->info("  Temporada {$season} pagina {$page}: " . count($games) . " jogos da API");
                    if (empty($games)) break;
                    foreach ($games as $g) {
                        $home = Team::where('api_id', $g['home_team']['id'] ?? 0)->first();
                        $away = Team::where('api_id', $g['visitor_team']['id'] ?? 0)->first();
                        if (!$home || !$away) {
                            $this->warn("    Jogo {$g['id']} pulado (time nao encontrado)");
                            continue;
                        }
                        Game::updateOrCreate(
                            ['api_id' => $g['id']],
                            [
                                'home_team_id' => $home->id,
                                'away_team_id' => $away->id,
                                'game_date' => $g['date'],
                                'status' => $g['status'] === 'Final' ? 'finished' : 'scheduled',
                                'home_score' => $g['home_team_score'] ?? 0,
                                'away_score' => $g['visitor_team_score'] ?? 0,
                                'season' => (string) $season,
                            ]
                        );
                        $count++;
                    }
                    $page++;
                }
            }
            $this->info('Jogos sincronizados: ' . $count);
        }

        $this->info('');
        $this->info('=== Status Final ===');
        $this->info('Times: ' . Team::count());
        $this->info('Jogos: ' . Game::count());
    }
}
