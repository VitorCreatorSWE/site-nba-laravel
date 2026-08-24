<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Post;
use App\Models\Team;
use App\Services\BalldontlieService;
use App\Services\NewsGenerator;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(BalldontlieService $balldontlie)
    {
        if (Team::count() === 0 || Game::count() === 0) {
            $this->syncAll($balldontlie);
        }

        if (Game::where('status', 'finished')->whereDoesntHave('post')->exists()) {
            (new NewsGenerator())->generateForAllFinishedGames();
        }

        $featuredPosts = Post::where('status', 'published')->with('user')->latest('published_at')->limit(3)->get();
        $latestPosts = Post::where('status', 'published')->with('user')->latest('published_at')->limit(6)->get();
        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])->where('game_date', '>=', now())->where('status', 'scheduled')->orderBy('game_date')->limit(5)->get();
        $liveGames = Game::with(['homeTeam', 'awayTeam'])->where('status', 'live')->get();
        $standings = Team::orderByDesc('championships_count')->limit(10)->get();

        return view('home', compact('featuredPosts', 'latestPosts', 'upcomingGames', 'liveGames', 'standings'));
    }

    private function syncAll(BalldontlieService $balldontlie): void
    {
        $balldontlie->syncTeams();
        $response = Http::withoutVerifying()->withHeaders(['Authorization' => config('services.balldontlie.key')])->timeout(10)->get(
            'https://api.balldontlie.io/v1/games', ['seasons[]' => 2025, 'per_page' => 100, 'page' => 1]
        );

        if (!$response->successful()) return;

        foreach ($response->json('data', []) as $game) {
            $home = Team::where('api_id', $game['home_team']['id'] ?? 0)->first();
            $away = Team::where('api_id', $game['visitor_team']['id'] ?? 0)->first();
            if (!$home || !$away) continue;

            Game::updateOrCreate(['api_id' => $game['id']], [
                'home_team_id' => $home->id,
                'away_team_id' => $away->id,
                'game_date' => substr($game['date'], 0, 10) . ' 20:00:00',
                'status' => $game['status'] === 'Final' ? 'finished' : 'scheduled',
                'home_score' => $game['home_team_score'] ?? 0,
                'away_score' => $game['visitor_team_score'] ?? 0,
                'season' => '2025',
            ]);
        }
    }
}
