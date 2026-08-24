@extends('layouts.app')

@section('title', $post->title . ' - Análises - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('analises.index') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> {{ __('messages.back') }} {{ __('messages.analysis') }}
                </a>
            </div>

            <article>
                <span class="badge bg-info mb-3">{{ __('messages.analysis') }}</span>

                <h1 class="mb-3">{{ $post->title }}</h1>

                <div class="d-flex align-items-center text-muted mb-4 flex-wrap gap-3">
                    <span><i class="bi bi-person"></i> {{ $post->user->name }}</span>
                    <span><i class="bi bi-calendar"></i> {{ $post->published_at->format('d/m/Y H:i') }}</span>
                    <span><i class="bi bi-eye"></i> {{ $post->views_count }} {{ __('messages.views') }}</span>
                </div>

                @if($post->cover_image)
                    <img src="{{ $post->cover_image }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">
                @endif

                <div class="content fs-5">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </article>

            <hr class="my-5">

            <section>
                <h3><i class="bi bi-chat-dots"></i> {{ __('messages.comments_count') }} ({{ $post->approvedComments->count() }})</h3>

                @auth
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" placeholder="Escreva seu comentário..." required>{{ old('content') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info">{{ __('messages.send_comment') }}</button>
                    </form>
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}">{{ __('messages.login') }}</a> {{ __('messages.login_to_comment') }}
                    </div>
                @endauth

                @forelse($post->approvedComments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">{{ __('messages.no_comments_first') }}</p>
                @endforelse
            </section>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-info">
                    <h5 class="mb-0 text-white"><i class="bi bi-graph-up"></i> {{ __('messages.analysis') }}</h5>
                </div>
                <div class="card-body">
                    @forelse($relatedPosts as $related)
                        <div class="d-flex mb-3">
                            @if($related->cover_image)
                                <img src="{{ $related->cover_image }}" class="rounded me-3" style="width:60px;height:60px;object-fit:cover;" alt="">
                            @else
                                <div class="rounded me-3 bg-info bg-gradient d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                    <i class="bi bi-graph-up text-white"></i>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('analises.show', $related->slug) }}" class="text-decoration-none fw-bold">{{ Str::limit($related->title, 50) }}</a>
                                <br>
                                <small class="text-muted">{{ $related->published_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('messages.no_analysis') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
