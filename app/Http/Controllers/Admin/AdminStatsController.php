<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameStat;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function index()
    {
        $stats = GameStat::with(['game.homeTeam', 'game.awayTeam', 'team'])
            ->latest()
            ->paginate(15);

        return view('admin.estatisticas.index', compact('stats'));
    }

    public function create()
    {
        $games = Game::orderBy('game_date', 'desc')->get();
        $teams = Team::orderBy('name')->get();

        return view('admin.estatisticas.create', compact('games', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'field_goals_made' => 'nullable|integer|min:0',
            'field_goals_attempted' => 'nullable|integer|min:0',
            'three_pointers_made' => 'nullable|integer|min:0',
            'three_pointers_attempted' => 'nullable|integer|min:0',
            'free_throws_made' => 'nullable|integer|min:0',
            'free_throws_attempted' => 'nullable|integer|min:0',
            'rebounds' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'turnovers' => 'nullable|integer|min:0',
            'points' => 'nullable|integer|min:0',
        ]);

        GameStat::create($validated);

        return redirect()->route('admin.estatisticas.index')
            ->with('success', 'EstatÃ­stica criada com sucesso!');
    }

    public function edit(GameStat $stat)
    {
        $games = Game::orderBy('game_date', 'desc')->get();
        $teams = Team::orderBy('name')->get();

        return view('admin.estatisticas.edit', compact('stat', 'games', 'teams'));
    }

    public function update(Request $request, GameStat $stat)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'field_goals_made' => 'nullable|integer|min:0',
            'field_goals_attempted' => 'nullable|integer|min:0',
            'three_pointers_made' => 'nullable|integer|min:0',
            'three_pointers_attempted' => 'nullable|integer|min:0',
            'free_throws_made' => 'nullable|integer|min:0',
            'free_throws_attempted' => 'nullable|integer|min:0',
            'rebounds' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'turnovers' => 'nullable|integer|min:0',
            'points' => 'nullable|integer|min:0',
        ]);

        $stat->update($validated);

        return redirect()->route('admin.estatisticas.index')
            ->with('success', 'EstatÃ­stica atualizada com sucesso!');
    }

    public function destroy(GameStat $stat)
    {
        $stat->delete();

        return redirect()->route('admin.estatisticas.index')
            ->with('success', 'EstatÃ­stica removida com sucesso!');
    }
}
