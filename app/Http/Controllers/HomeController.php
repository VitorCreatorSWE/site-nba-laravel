<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Post;
use App\Models\Team;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::where('status', 'published')->with('user')->latest('published_at')->limit(3)->get();
        $latestPosts = Post::where('status', 'published')->with('user')->latest('published_at')->limit(6)->get();
        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])->where('game_date', '>=', now())->where('status', 'scheduled')->orderBy('game_date')->limit(5)->get();
        $liveGames = Game::with(['homeTeam', 'awayTeam'])->where('status', 'live')->get();
        $standings = Team::orderByDesc('championships_count')->limit(10)->get();

        return view('home', compact('featuredPosts', 'latestPosts', 'upcomingGames', 'liveGames', 'standings'));
    }
}
