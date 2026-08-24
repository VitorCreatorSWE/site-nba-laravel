@extends('layouts.app')

@section('title', __('messages.news'))

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="bi bi-newspaper"></i> {{ __('messages.news') }}</h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('posts.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                <select name="category" class="form-select" style="width:auto;" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all') }}</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    @if($post->cover_image)
                        <img src="{{ $post->cover_image }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
                    @else
                        <div class="card-img-top bg-primary bg-gradient d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-newspaper text-white" style="font-size:3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-primary category-badge">{{ $post->category_label }}</span>
                        </div>
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted"><i class="bi bi-newspaper"></i> Basq NBA</small>
                                <br>
                                <small class="text-muted">{{ $post->published_at->format('d/m/Y') }}</small>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.read_more') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-newspaper text-muted" style="font-size:4rem;"></i>
                <p class="mt-3 text-muted">{{ __('messages.no_posts') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection