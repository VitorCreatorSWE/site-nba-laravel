<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminPlayerController extends Controller
{
    public function index(Request $request)
    {
        $players = Player::query()
            ->with('team')
            ->search($request->query('q'))
            ->when($request->filled('team'), function ($query) use ($request) {
                $query->whereHas('team', fn ($q) => $q->where('abbreviation', $request->query('team')));
            })
            ->when($request->query('active') === '1', fn ($query) => $query->active())
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(48)
            ->withQueryString();

        $teams = Team::orderBy('name')->get();

        return view('admin.players.index', compact('players', 'teams'));
    }

    public function edit(Player $player)
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:50',
            'jersey_number' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:20',
            'height_meters' => 'nullable|numeric|min:1.5|max:2.6',
            'weight' => 'nullable|integer|min:100|max:400',
            'weight_kg' => 'nullable|numeric|min:50|max:160',
            'birth_date' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'college' => 'nullable|string|max:255',
            'draft_year' => 'nullable|integer|min:1946|max:' . date('Y'),
            'draft_round' => 'nullable|integer|min:1|max:10',
            'draft_number' => 'nullable|integer|min:1|max:60',
            'career_start' => 'nullable|integer|min:1946|max:' . date('Y'),
            'seasons_played' => 'nullable|integer|min:0|max:40',
            'is_active' => 'nullable|boolean',
            'season' => 'nullable|integer|min:1996|max:2100',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Um campo opcional ausente nao vem em $validated, entao o acesso
        // direto a chave estouraria com "Undefined array key".
        $validated['team_id'] = $validated['team_id'] ?? null;

        $player->update($validated);

        return redirect()
            ->route('admin.players.index')
            ->with('success', "Jogador {$player->full_name} atualizado com sucesso!");
    }
}