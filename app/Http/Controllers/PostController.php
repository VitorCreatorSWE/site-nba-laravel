<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('status', 'published')
            ->with('user');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $categories = [
            'noticia' => 'Notícias',
            'analise' => 'Análises',
            'opiniao' => 'Opiniões',
            'destaque' => 'Destaques',
        ];

        return view('noticias.index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        abort_unless($post->isPublished(), 404);

        $post->incrementViews();
        $post->load(['user', 'approvedComments.user']);

        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->limit(3)
            ->get();

        return view('noticias.show', compact('post', 'relatedPosts'));
    }

    public function recentes(Request $request)
    {
        $query = Post::where('status', 'published')
            ->where('category', 'recente')
            ->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('recentes.index', compact('posts'));
    }

    public function showRecente(Post $post)
    {
        abort_unless($post->isPublished() && $post->category === 'recente', 404);

        $post->incrementViews();
        $post->load(['user', 'approvedComments.user']);

        $relatedPosts = Post::where('status', 'published')
            ->where('category', 'recente')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('recentes.show', compact('post', 'relatedPosts'));
    }
}