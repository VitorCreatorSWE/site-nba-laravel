@extends('layouts.app')

@section('title', __('messages.games') . ' - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-calendar3"></i> {{ __('messages.games') }}</h1>
            <p class="text-muted">{{ __('messages.games_subtitle') }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('jogos.index') }}" class="d-flex gap-2 flex-wrap">
                <select name="team" class="form-select" style="max-width:250px;" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all_teams') }}</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all_statuses') }}</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>{{ __('messages.scheduled_plural') }}</option>
                    <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>{{ __('messages.live_upper') }}</option>
                    <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>{{ __('messages.finished_plural') }}</option>
                </select>
            </form>
        </div>
    </div>

    @if($games->count() > 0)
        <div class="row g-4">
            @foreach($games as $game)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm {{ $game->isLive() ? 'border-success border-2' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                @if($game->isLive())
                                    <span class="badge bg-danger"><i class="bi bi-broadcast"></i> {{ __('messages.live_upper') }}</span>
                                @elseif($game->isFinished())
                                    <span class="badge bg-secondary">{{ __('messages.finished') }}</span>
                                @else
                                    <span class="badge bg-info">{{ __('messages.scheduled') }}</span>
                                @endif
                                <small class="text-muted">{{ $game->game_date->format('d/m/Y H:i') }}</small>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-center flex-fill">
                                    <div class="fw-bold">{{ $game->homeTeam->abbreviation }}</div>
                                    <div class="text-muted small">{{ $game->homeTeam->name }}</div>
                                    @if($game->isLive() || $game->isFinished())
                                        <div class="display-6 fw-bold">{{ $game->home_score }}</div>
                                    @endif
                                </div>
                                <div class="px-3">
                                    <span class="badge bg-dark rounded-pill px-3 py-2">VS</span>
                                </div>
                                <div class="text-center flex-fill">
                                    <div class="fw-bold">{{ $game->awayTeam->abbreviation }}</div>
                                    <div class="text-muted small">{{ $game->awayTeam->name }}</div>
                                    @if($game->isLive() || $game->isFinished())
                                        <div class="display-6 fw-bold">{{ $game->away_score }}</div>
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
                            <a href="{{ route('jogos.show', $game->id) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_details') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $games->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-calendar3 text-muted" style="font-size:4rem;"></i>
            <p class="mt-3 text-muted">{{ __('messages.no_games') }}</p>
        </div>
    @endif
</div>
@endsection
