@extends('layouts.app')

@section('title', 'Notícias - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-newspaper"></i> Notícias</h1>
            <p class="text-muted">Fique por dentro de tudo que acontece na NBA</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('noticias.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar notícia..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @php
                $images = ['FxxaP.jpg', 'ZiRD0.jpg', 'sKunT.jpg', 'so1kB.jpg', 'ZwTSd.jpg', 'upJiz.jpg'];
                $shuffled = shuffle($images) ? $images : $images;
                $prevImg = null;
            @endphp
            @foreach($posts as $index => $post)
                @php
                    $currentIndex = $index % 6;
                    if ($currentIndex === 0 && $index > 0) {
                        $shuffled = $images;
                        shuffle($shuffled);
                    }
                    $currentImg = $shuffled[$currentIndex];
                    if ($currentImg === $prevImg && count($images) > 1) {
                        $currentIndex = ($currentIndex + 1) % 6;
                        $currentImg = $shuffled[$currentIndex];
                    }
                    $prevImg = $currentImg;
                    $randomImage = asset('images/' . $currentImg);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $randomImage }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $post->category_label }}</span>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-newspaper"></i> Basq NBA
                                    <span class="mx-1"></span>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('noticias.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">Ler mais</a>
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
            <i class="bi bi-newspaper text-muted" style="font-size:4rem;"></i>
            <p class="mt-3 text-muted">Nenhuma notícia encontrada.</p>
        </div>
    @endif
</div>
@endsection
