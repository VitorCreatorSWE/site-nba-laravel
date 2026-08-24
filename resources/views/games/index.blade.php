@extends('layouts.app')

@section('title', __('messages.games_schedule'))

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="bi bi-calendar3"></i> {{ __('messages.games_schedule') }}</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('games.index') }}" class="d-flex gap-2">
                <select name="team" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all_teams') }}</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>{{ __('messages.scheduled') }}</option>
                    <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>{{ __('messages.live') }}</option>
                    <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>{{ __('messages.finished') }}</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($games as $game)
            <div class="col-md-6 col-lg-4">
                <div class="card {{ $game->isLive() ? 'card-live' : 'card-game' }} h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if($game->isLive())
                                <span class="badge bg-danger"><i class="bi bi-broadcast"></i> {{ __('messages.live') }}</span>
                            @elseif($game->isFinished())
                                <span class="badge bg-secondary">{{ __('messages.finished') }}</span>
                            @else
                                <span class="badge bg-info">{{ __('messages.scheduled') }}</span>
                            @endif
                            <small class="text-muted">{{ $game->game_date->format('d/m/Y H:i') }}</small>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-center flex-fill">
                                <img src="{{ $game->homeTeam->logo }}" alt="{{ $game->homeTeam->name }}" class="team-logo mb-2">
                                <div class="fw-bold">{{ $game->homeTeam->abbreviation }}</div>
                                <div class="text-muted small">{{ $game->homeTeam->name }}</div>
                                @if($game->isLive() || $game->isFinished())
                                    <div class="h4 mb-0 mt-1">{{ $game->home_score }}</div>
                                @endif
                            </div>
                            <div class="px-3">
                                <span class="badge bg-dark rounded-pill px-3 py-2">VS</span>
                            </div>
                            <div class="text-center flex-fill">
                                <img src="{{ $game->awayTeam->logo }}" alt="{{ $game->awayTeam->name }}" class="team-logo mb-2">
                                <div class="fw-bold">{{ $game->awayTeam->abbreviation }}</div>
                                <div class="text-muted small">{{ $game->awayTeam->name }}</div>
                                @if($game->isLive() || $game->isFinished())
                                    <div class="h4 mb-0 mt-1">{{ $game->away_score }}</div>
                                @endif
                            </div>
                        </div>

                        @if($game->arena)
                            <div class="text-center mt-3">
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $game->arena }}</small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a href="{{ route('games.show', $game) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.details') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-calendar3 text-muted" style="font-size:4rem;"></i>
                <p class="mt-3 text-muted">{{ __('messages.no_games') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $games->links() }}
    </div>
</div>
@endsection