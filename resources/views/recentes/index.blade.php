@extends('layouts.app')

@section('title', 'Notícias Recentes - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-lightning"></i> Notícias Recentes</h1>
            <p class="text-muted">As últimas notícias e atualizações da NBA em tempo real</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('recentes.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar notícia recente..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-warning"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-warning">
                        @if($post->cover_image)
                            <img src="{{ $post->cover_image }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
                        @else
                            <div class="card-img-top bg-warning bg-gradient d-flex align-items-center justify-content-center" style="height:200px;">
                                <i class="bi bi-lightning text-dark" style="font-size:3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2"><i class="bi bi-lightning-fill"></i> Recente</span>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $post->user->name }}
                                    <span class="mx-1">•</span>
                                    {{ $post->published_at->format('d/m/Y H:i') }}
                                </small>
                                <a href="{{ route('recentes.show', $post->slug) }}" class="btn btn-sm btn-outline-warning">Ler mais</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-lightning text-muted" style="font-size:4rem;"></i>
            <p class="mt-3 text-muted">Nenhuma notícia recente encontrada.</p>
        </div>
    @endif
</div>
@endsection