<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\BalldontlieService;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;

class NbaSync extends Command
{
    protected $signature = 'nba:sync {--only= : teams,players,games}';
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

        if (!$only || $only === 'players') {
            $this->info('Sincronizando jogadores...');
            $page = 1;
            $count = 0;
            while ($page <= 10) {
                $players = $balldontlie->getPlayers(['per_page' => 100, 'page' => $page]);
                $this->info("  Pagina {$page}: " . count($players) . " jogadores da API");
                if (empty($players)) break;
                foreach ($players as $p) {
                    $team = Team::where('api_id', $p['team']['id'] ?? 0)->first();
                    $heightCm = null;
                    if (!empty($p['height'])) {
                        $parts = explode('-', $p['height']);
                        if (count($parts) === 2) {
                            $heightCm = round(((int)$parts[0] * 30.48) + ((int)$parts[1] * 2.54), 2);
                        }
                    }
                    $weightKg = null;
                    if (!empty($p['weight']) && is_numeric($p['weight'])) {
                        $weightKg = round((float)$p['weight'] / 2.205, 2);
                    }
                    Player::updateOrCreate(
                        ['api_id' => $p['id']],
                        [
                            'full_name' => $p['first_name'] . ' ' . $p['last_name'],
                            'position' => $p['position'] ?? 'Unknown',
                            'team_id' => $team?->id,
                            'jersey_number' => $p['jersey_number'] ?? null,
                            'height_cm' => $heightCm,
                            'weight_kg' => $weightKg,
                            'nationality' => $p['country'] ?? null,
                        ]
                    );
                    $count++;
                }
                $page++;
            }
            $this->info('Jogadores sincronizados: ' . $count);
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
        $this->info('Jogadores: ' . Player::count());
        $this->info('Jogos: ' . Game::count());
    }
}
