<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Game;
use App\Models\User;
use App\Models\Donation;
use App\Models\Comment;
use App\Models\Team;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_games' => Game::count(),
            'total_users' => User::count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('approved', false)->count(),
            'total_donations' => Donation::where('status', 'confirmed')->sum('amount'),
            'total_teams' => Team::count(),
        ];

        $recentPosts = Post::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentComments = Comment::with(['user', 'post'])
            ->latest()
            ->limit(5)
            ->get();

        $recentDonations = Donation::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentComments', 'recentDonations'));
    }
}
