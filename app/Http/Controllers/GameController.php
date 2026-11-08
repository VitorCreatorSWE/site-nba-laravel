<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Services\ApiSportsService;
use App\Services\GameAnalysis;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::orderBy('name')->get();

        $query = Game::with(['homeTeam', 'awayTeam']);

        if ($request->filled('team')) {
            $teamId = $request->team;
            $query->where(function ($q) use ($teamId) {
                $q->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $games = $query->orderBy('game_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('jogos.index', compact('teams', 'games'));
    }

    public function live(ApiSportsService $apiSports)
    {
        $liveGames = $apiSports->getLiveGames();

        return view('jogos.live', compact('liveGames'));
    }

    public function show(Game $game)
    {
        $game->load(['homeTeam', 'awayTeam', 'stats.team']);

        // Lê o texto ja salvo no idioma ativo. Usar generate() aqui fazia o
        // paragrafo trocar de variante a cada visita, porque o gerador sorteia
        // um template aleatorio.
        $analysis = (new GameAnalysis())->forGame($game);

        return view('jogos.show', compact('game', 'analysis'));
    }

    public function syncGames(ApiSportsService $apiSports)
    {
        $apiSports->syncGames();

        return redirect()->route('jogos.index')
            ->with('success', 'Jogos sincronizados com sucesso!');
    }
}