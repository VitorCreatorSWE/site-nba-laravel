@extends('layouts.app')

@section('title', __('messages.news') . ' - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-newspaper"></i> {{ __('messages.news') }}</h1>
            <p class="text-muted">{{ __('messages.news_subtitle') }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('noticias.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_news') }}" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $post->cover_image }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
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
                                <a href="{{ route('noticias.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.read_more') }}</a>
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
            <p class="mt-3 text-muted">{{ __('messages.no_news') }}</p>
        </div>
    @endif
</div>
@endsection
