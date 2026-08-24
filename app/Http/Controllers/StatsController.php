<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use App\Models\GameStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GameAnalysis;

class StatsController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name')->get();

        return view('estatisticas.index', compact('teams'));
    }

    public function showTeam(Team $team)
    {
        $gamesWon = Game::where('status', 'finished')
            ->where(function ($q) use ($team) {
                $q->where(function ($q2) use ($team) {
                    $q2->where('home_team_id', $team->id)
                       ->whereColumn('home_score', '>', 'away_score');
                })->orWhere(function ($q2) use ($team) {
                    $q2->where('away_team_id', $team->id)
                       ->whereColumn('away_score', '>', 'home_score');
                });
            })->count();

        $gamesTotal = Game::where('status', 'finished')
            ->where('home_team_id', $team->id)
            ->orWhere('away_team_id', $team->id)
            ->count();

        $winRate = $gamesTotal > 0 ? round(($gamesWon / $gamesTotal) * 100, 1) : 0;

        // Get recent games with analysis
        $recentGames = Game::where('status', 'finished')
            ->where(function ($q) use ($team) {
                $q->where('home_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
            })
            ->with('homeTeam', 'awayTeam')
            ->orderByDesc('game_date')
            ->take(5)
            ->get()
            ->map(function ($game) {
                $homeWon = $game->home_score > $game->away_score;
                $winner = $homeWon ? $game->homeTeam : $game->awayTeam;
                $loser = $homeWon ? $game->awayTeam : $game->homeTeam;
                $score = $game->home_score . '-' . $game->away_score;

                $analysisService = new GameAnalysis();
                $analysis = $analysisService->generate(
                    $winner->name,
                    $loser->name,
                    $score,
                    $game->game_date
                );

                return [
                    'game' => $game,
                    'analysis' => $analysis,
                ];
            });

        return view('estatisticas.team', compact(
            'team',
            'gamesWon',
            'gamesTotal',
            'winRate',
            'recentGames'
        ));
    }

}
