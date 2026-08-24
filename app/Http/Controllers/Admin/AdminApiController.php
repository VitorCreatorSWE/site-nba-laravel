<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

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
