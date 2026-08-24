<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->latest('game_date')
            ->paginate(15);

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.games.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'game_date' => 'required|date',
            'arena' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,live,finished',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'season' => 'nullable|string|max:9',
            'admin_analysis' => 'nullable|string',
        ]);

        Game::create($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'Jogo criado com sucesso!');
    }

    public function edit(Game $game)
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.games.edit', compact('game', 'teams'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'game_date' => 'required|date',
            'arena' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,live,finished',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'season' => 'nullable|string|max:9',
            'admin_analysis' => 'nullable|string',
        ]);

        $game->update($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'Jogo atualizado com sucesso!');
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'Jogo removido com sucesso!');
    }
}
