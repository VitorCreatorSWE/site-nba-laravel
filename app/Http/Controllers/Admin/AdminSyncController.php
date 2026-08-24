<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BalldontlieService;
use App\Models\Team;
use App\Models\Game;

class AdminSyncController extends Controller
{
    public function syncTeams(BalldontlieService $balldontlie)
    {
        $balldontlie->syncTeams();

        return back()->with('success', 'Times sincronizados com sucesso! (' . Team::count() . ' times)');
    }

    public function syncGames(BalldontlieService $balldontlie)
    {
        $totalSynced = 0;

        foreach ([2023, 2024] as $season) {
            $page = 1;
            while ($page <= 10) {
                $games = $balldontlie->getGames([
                    'seasons[]' => $season,
                    'per_page' => 100,
                    'page' => $page,
                ]);

                if (empty($games)) break;

                foreach ($games as $gameData) {
                    $homeTeam = Team::where('api_id', $gameData['home_team']['id'] ?? 0)->first();
                    $awayTeam = Team::where('api_id', $gameData['visitor_team']['id'] ?? 0)->first();

                    if (!$homeTeam || !$awayTeam) continue;

                    Game::updateOrCreate(
                        ['api_id' => $gameData['id']],
                        [
                            'home_team_id' => $homeTeam->id,
                            'away_team_id' => $awayTeam->id,
                            'game_date' => $gameData['date'],
                            'status' => $gameData['status'] === 'Final' ? 'finished' : 'scheduled',
                            'home_score' => $gameData['home_team_score'] ?? 0,
                            'away_score' => $gameData['visitor_team_score'] ?? 0,
                            'season' => (string) $season,
                        ]
                    );
                    $totalSynced++;
                }

                $page++;
            }
        }

        return back()->with('success', "{$totalSynced} jogos sincronizados com sucesso!");
    }
}
