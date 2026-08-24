<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->where('category', 'analise')
            ->with('user')
            ->latest('published_at')
            ->paginate(12);

        return view('analysis.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->isPublished() && $post->category === 'analise', 404);

        $post->incrementViews();
        $post->load(['user', 'approvedComments.user']);

        $relatedPosts = Post::where('status', 'published')
            ->where('category', 'analise')
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->latest('published_at')
            ->get();

        return view('analysis.show', compact('post', 'relatedPosts'));
    }
}