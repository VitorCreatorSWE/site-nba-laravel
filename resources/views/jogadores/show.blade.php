@extends('layouts.app')

@section('title', $player->full_name . ' - Basq NBA')

@section('content')
<style>
    .player-placeholder {
        width: 220px;
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: 600;
        letter-spacing: .05em;
        color: var(--bs-secondary-color, #6c757d);
        background: var(--bs-tertiary-bg, #f8f9fa);
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 50%;
    }
</style>
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jogadores.index') }}">{{ __('messages.players') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $player->full_name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4 text-center">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($player->photo)
                        <img src="{{ $player->photo }}" alt="{{ $player->full_name }}"
                             class="img-fluid mb-3" style="max-height:320px;object-fit:contain;object-position:top;">
                    @else
                        <div class="player-placeholder mb-3 mx-auto" role="img"
                             aria-label="{{ $player->full_name }}">
                            {{ $player->initials }}
                        </div>
                    @endif
                    <h4 class="mb-1">{{ $player->full_name }}</h4>
                    <p class="text-muted mb-2">
                        @if($player->team)
                            <a href="{{ route('estatisticas.team', $player->team) }}" class="text-decoration-none">
                                {{ $player->team->name }}
                            </a>
                        @else
                            {{ __('messages.free_agent') }}
                        @endif
                    </p>
                    <p class="mb-0">
                        @if($player->jersey_number)
                            <span class="badge bg-dark">#{{ $player->jersey_number }}</span>
                        @endif
                        @if($player->position)
                            <span class="badge bg-secondary">{{ $player->position }}</span>
                        @endif
                        <span class="badge bg-{{ $player->is_active ? 'success' : 'warning text-dark' }}">
                            {{ $player->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">{{ __('messages.player_profile') }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">{{ __('messages.height') }}</dt>
                        <dd class="col-sm-8">
                            {{ $player->height ?: '—' }}
                            @if($player->height_inches)
                                <span class="text-muted small">
                                    ({{ $player->height_inches }} in &middot; {{ round((float) $player->height_meters * 100) }} cm)
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.weight') }}</dt>
                        <dd class="col-sm-8">
                            @if($player->weight)
                                {{ $player->weight }} lb
                                @if($player->weight_kg)
                                    <span class="text-muted small">({{ round((float) $player->weight_kg) }} kg)</span>
                                @endif
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.birth_date') }}</dt>
                        <dd class="col-sm-8">
                            @if($player->birth_date)
                                {{ $player->birth_date->format('d/m/Y') }}
                                <span class="text-muted small">
                                    ({{ $player->age }} {{ __('messages.years') }})
                                </span>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.country') }}</dt>
                        <dd class="col-sm-8">{{ $player->country ?: '—' }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.college') }}</dt>
                        <dd class="col-sm-8">{{ $player->college ?: '—' }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.career_start') }}</dt>
                        <dd class="col-sm-8">{{ $player->career_start ?: '—' }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.seasons_played') }}</dt>
                        <dd class="col-sm-8">{{ $player->seasons_played ?: '—' }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('messages.draft') }}</dt>
                        <dd class="col-sm-8">
                            @if($player->draft_year)
                                {{ $player->draft_year }}
                                @if($player->draft_round)
                                    &middot; {{ __('messages.round') }} {{ $player->draft_round }}
                                @endif
                                @if($player->draft_number)
                                    &middot; {{ __('messages.pick') }} {{ $player->draft_number }}
                                @endif
                            @else
                                {{ __('messages.undrafted') }}
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            @if($player->team)
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">{{ $player->team->name }} — {{ __('messages.roster') }}</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($player->team->players()->orderBy('last_name')->orderBy('first_name')->get() as $teammate)
                            <a href="{{ route('jogadores.show', $teammate) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $teammate->id === $player->id ? 'active' : '' }}">
                                <span>{{ $teammate->full_name }}</span>
                                <span class="text-muted small {{ $teammate->id === $player->id ? 'text-white-50' : '' }}">
                                    {{ $teammate->position ?: '—' }}
                                    @if($teammate->jersey_number)
                                        &middot; #{{ $teammate->jersey_number }}
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
