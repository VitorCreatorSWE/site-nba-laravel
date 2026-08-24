<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Game;
use App\Models\User;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_games' => Game::count(),
            'total_users' => User::count(),
            'total_donations' => Donation::where('status', 'confirmed')->sum('amount'),
        ];

        $recentPosts = Post::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentDonations = Donation::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentDonations'));
    }
}