@extends('layouts.app')

@section('title', __('messages.players') . ' - Basq NBA')

@section('content')
<style>
    .player-headshot {
        width: 100%;
        height: 120px;
        object-fit: contain;
        object-position: top;
        background: var(--bs-tertiary-bg, #f8f9fa);
        border-radius: 0.375rem;
    }

    .player-placeholder {
        width: 100%;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        font-weight: 600;
        letter-spacing: .05em;
        color: var(--bs-secondary-color, #6c757d);
        background: var(--bs-tertiary-bg, #f8f9fa);
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 0.375rem;
    }
</style>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-person-badge"></i> {{ __('messages.players') }}</h1>
            <p class="text-muted">{{ __('messages.players_subtitle') }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('jogadores.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted" for="q">{{ __('messages.search') }}</label>
                    <input type="search" name="q" id="q" value="{{ request('q') }}" class="form-control"
                           placeholder="{{ __('messages.search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted" for="team">{{ __('messages.filter_by_team') }}</label>
                    <select name="team" id="team" class="form-select">
                        <option value="">{{ __('messages.all_teams') }}</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->abbreviation }}" @selected(request('team') === $team->abbreviation)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted" for="position">{{ __('messages.filter_by_position') }}</label>
                    <select name="position" id="position" class="form-select">
                        <option value="">{{ __('messages.all') }}</option>
                        @foreach($positions as $position)
                            <option value="{{ $position }}" @selected(request('position') === $position)>
                                {{ $position }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="active" value="all" id="active"
                               @checked(! $onlyActive)>
                        <label class="form-check-label" for="active">{{ __('messages.show_all_players') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{ __('messages.filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    @if($players->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-person-x text-muted" style="font-size:4rem;"></i>
            <p class="mt-3 text-muted">{{ __('messages.no_players') }}</p>
            <p class="text-muted small">{{ __('messages.no_players_sync') }}</p>
        </div>
    @else
        <p class="text-muted small">
            {{ $players->total() }}
            @if($onlyActive)
                {{ __('messages.active_players') }} ({{ $totalActive }} {{ __('messages.players') }})
            @else
                {{ __('messages.players') }} ({{ $totalAll }})
            @endif
        </p>

        <div class="row g-4">
            @foreach($players as $player)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="{{ route('jogadores.show', $player) }}" class="text-decoration-none">
                        <div class="card h-100 text-center shadow-sm player-card" style="transition: transform 0.2s;"
                             onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                            <div class="card-body">
                                @if($player->photo)
                                    <img src="{{ $player->photo }}" alt="{{ $player->full_name }}"
                                         class="mb-3 player-headshot" loading="lazy">
                                @else
                                    <div class="player-placeholder mb-3" role="img"
                                         aria-label="{{ $player->full_name }}">
                                        {{ $player->initials }}
                                    </div>
                                @endif
                                <h6 class="card-title mb-1">{{ $player->full_name }}</h6>
                                <p class="text-muted small mb-1">
                                    {{ $player->team?->abbreviation ?? '---' }}
                                    @if($player->jersey_number)
                                        &middot; #{{ $player->jersey_number }}
                                    @endif
                                </p>
                                @if($player->position)
                                    <span class="badge bg-secondary">{{ $player->position }}</span>
                                @endif
                                @unless($player->is_active)
                                    <span class="badge bg-warning text-dark">{{ __('messages.inactive') }}</span>
                                @endunless
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $players->links() }}
        </div>
    @endif
</div>
@endsection
