<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name')->paginate(30);

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'api_id' => 'required|integer|unique:teams,api_id',
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10|unique:teams,abbreviation',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'conference' => 'required|in:East,West',
            'division' => 'required|string|max:255',
            'arena_name' => 'nullable|string|max:255',
            'arena_capacity' => 'nullable|integer|min:0',
            'arena_location' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'championships_count' => 'nullable|integer|min:0',
            'logo_url' => 'nullable|url|max:500',
        ]);

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Time criado com sucesso!');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10|unique:teams,abbreviation,' . $team->id,
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'conference' => 'required|in:East,West',
            'division' => 'required|string|max:255',
            'arena_name' => 'nullable|string|max:255',
            'arena_capacity' => 'nullable|integer|min:0',
            'arena_location' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'championships_count' => 'nullable|integer|min:0',
            'logo_url' => 'nullable|url|max:500',
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Time atualizado com sucesso!');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Time removido com sucesso!');
    }
}