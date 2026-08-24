@extends('layouts.app')

@section('title', __('messages.home'))

@section('content')
<div class="hero-section" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/banner.jpg') }}') center/cover no-repeat;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3 text-white">🏀 {{ __('messages.welcome') }}</h1>
        <p class="lead mb-4 text-white">{{ __('messages.for_nba_fans') }}</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('noticias.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-newspaper"></i> {{ __('messages.news') }}
            </a>
            <a href="{{ route('jogos.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-calendar3"></i> {{ __('messages.games') }}
            </a>
        </div>
    </div>
</div>

@if($liveGames->count() > 0)
<section class="py-5 bg-success bg-opacity-10">
    <div class="container">
        <h2 class="mb-4"><i class="bi bi-broadcast text-danger"></i> {{ __('messages.live_games') }}</h2>
        <div class="row g-4">
            @foreach($liveGames as $game)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-game h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-danger">{{ __('messages.live') }}</span>
                                <small class="text-muted">{{ $game->game_date->format('d/m H:i') }}</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-center">
                                    <img src="{{ $game->homeTeam->logo }}" alt="{{ $game->homeTeam->name }}" class="team-logo mb-2">
                                    <div class="fw-bold">{{ $game->homeTeam->abbreviation }}</div>
                                    <div class="h4 mb-0">{{ $game->home_score }}</div>
                                </div>
                                <div class="px-3">
                                    <span class="badge bg-dark">VS</span>
                                </div>
                                <div class="text-center">
                                    <img src="{{ $game->awayTeam->logo }}" alt="{{ $game->awayTeam->name }}" class="team-logo mb-2">
                                    <div class="fw-bold">{{ $game->awayTeam->abbreviation }}</div>
                                    <div class="h4 mb-0">{{ $game->away_score }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($upcomingGames->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-calendar3"></i> {{ __('messages.upcoming_games') }}</h2>
            <a href="{{ route('jogos.index') }}" class="btn btn-outline-primary">{{ __('messages.all') }} →</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('messages.game') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.venue') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingGames as $game)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $game->homeTeam->logo }}" alt="" class="team-logo me-2" style="width:30px;height:30px;">
                                    <strong>{{ $game->homeTeam->abbreviation }}</strong>
                                    <span class="mx-2">vs</span>
                                    <img src="{{ $game->awayTeam->logo }}" alt="" class="team-logo me-2" style="width:30px;height:30px;">
                                    <strong>{{ $game->awayTeam->abbreviation }}</strong>
                                </div>
                            </td>
                            <td>{{ $game->game_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $game->arena ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ __('messages.scheduled') }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

<section class="py-5 bg-body-secondary">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-newspaper"></i> {{ __('messages.recent_news') }}</h2>
            <a href="{{ route('noticias.index') }}" class="btn btn-outline-primary">{{ __('messages.all') }} →</a>
        </div>
        <div class="row g-4">
            @forelse($latestPosts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <img src="{{ $post->cover_image }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover;">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-primary category-badge">{{ $post->category_label }}</span>
                            </div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($post->excerpt ?? Str::limit(strip_tags($post->content), 100), 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $post->published_at->format('d/m/Y') }}</small>
                                <a href="{{ route('noticias.show', $post) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.read_more') }}</a>
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
    </div>
</section>
@endsection
