<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminApiController extends Controller
{
    public function searchTeams(Request $request)
    {
        $search = $request->input('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $teams = Team::where('name', 'like', "%{$search}%")
            ->orWhere('abbreviation', 'like', "%{$search}%")
            ->orWhere('city', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($team) => [
                'id' => $team->id,
                'name' => $team->name,
                'abbreviation' => $team->abbreviation,
                'city' => $team->city,
                'arena_name' => $team->arena_name,
                'logo' => $team->logo,
            ]);

        return response()->json($teams);
    }

    public function searchPlayers(Request $request)
    {
        $search = $request->input('q', '');
        $teamId = $request->input('team_id');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $query = Player::where('full_name', 'like', "%{$search}%");

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $players = $query->limit(10)
            ->get()
            ->map(fn($player) => [
                'id' => $player->id,
                'full_name' => $player->full_name,
                'position' => $player->position,
                'jersey_number' => $player->jersey_number,
                'team_name' => $player->team->name ?? '-',
                'photo' => $player->photo,
            ]);

        return response()->json($players);
    }

    public function getTeamInfo(Request $request)
    {
        $teamId = $request->input('team_id');

        $team = Team::find($teamId);

        if (!$team) {
            return response()->json(['error' => 'Time não encontrado'], 404);
        }

        return response()->json([
            'id' => $team->id,
            'name' => $team->name,
            'abbreviation' => $team->abbreviation,
            'city' => $team->city,
            'conference' => $team->conference,
            'division' => $team->division,
            'arena_name' => $team->arena_name,
            'arena_capacity' => $team->arena_capacity,
            'logo' => $team->logo,
            'players' => $team->players->map(fn($p) => [
                'id' => $p->id,
                'full_name' => $p->full_name,
                'position' => $p->position,
                'jersey_number' => $p->jersey_number,
            ]),
        ]);
    }

    public function searchNbaTeams(Request $request)
    {
        $search = $request->input('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $teams = Team::where('name', 'like', "%{$search}%")
            ->orWhere('abbreviation', 'like', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($teams);
    }
}
