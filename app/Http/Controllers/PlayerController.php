<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::orderBy('name')->get();

        // "all" exposes the all-time players that balldontlie contributes when
        // the api-sports quota is exhausted; the default view is the active roster.
        $onlyActive = $request->query('active') !== 'all';

        $players = Player::query()
            ->with('team')
            ->search($request->query('q'))
            ->when($onlyActive, fn ($query) => $query->active())
            ->when($request->filled('team'), function ($query) use ($request) {
                $query->whereHas('team', fn ($q) => $q->where('abbreviation', $request->query('team')));
            })
            ->when($request->filled('position'), fn ($query) => $query->where('position', $request->query('position')))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(36)
            ->withQueryString();

        $positions = Player::query()
            ->whereNotNull('position')
            ->when($onlyActive, fn ($query) => $query->active())
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        $totalActive = Player::where('is_active', true)->count();
        $totalAll = Player::count();

        return view('jogadores.index', compact('players', 'teams', 'positions', 'onlyActive', 'totalActive', 'totalAll'));
    }

    public function show(Player $player)
    {
        $player->load('team');

        return view('jogadores.show', compact('player'));
    }
}
