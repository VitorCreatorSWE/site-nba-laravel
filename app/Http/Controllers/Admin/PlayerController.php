<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')
            ->orderBy('full_name')
            ->paginate(30);

        return view('admin.players.index', compact('players'));
    }

    public function create()
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.players.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'api_id' => 'required|integer|unique:players,api_id',
            'team_id' => 'nullable|exists:teams,id',
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'wingspan_cm' => 'nullable|numeric|min:0|max:300',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'jersey_number' => 'nullable|string|max:5',
            'photo_url' => 'nullable|url|max:500',
        ]);

        Player::create($validated);

        return redirect()->route('admin.players.index')
            ->with('success', 'Jogador criado com sucesso!');
    }

    public function edit(Player $player)
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'team_id' => 'nullable|exists:teams,id',
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'wingspan_cm' => 'nullable|numeric|min:0|max:300',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'jersey_number' => 'nullable|string|max:5',
            'photo_url' => 'nullable|url|max:500',
        ]);

        $player->update($validated);

        return redirect()->route('admin.players.index')
            ->with('success', 'Jogador atualizado com sucesso!');
    }

    public function destroy(Player $player)
    {
        $player->delete();

        return redirect()->route('admin.players.index')
            ->with('success', 'Jogador removido com sucesso!');
    }
}