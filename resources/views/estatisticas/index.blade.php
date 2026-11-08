@extends('layouts.app')

@section('title', __('messages.stats') . ' - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-bar-chart"></i> {{ __('messages.stats') }}</h1>
            <p class="text-muted">{{ __('messages.stats_subtitle') }}</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($teams as $team)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('estatisticas.team', $team) }}" class="text-decoration-none">
                    <div class="card h-100 text-center team-card shadow-sm d-flex" style="transition: transform 0.2s;min-height:200px;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title mb-1">{{ $team->name }}</h5>
                            <p class="text-muted mb-3">{{ $team->city }}</p>
                            <div class="d-flex flex-column align-items-center gap-1 mt-auto">
                                <small class="text-muted">{{ $team->conference === 'East' ? 'Eastern' : 'Western' }} Conference</small>
                                <small class="text-muted">{{ $team->division }} Division</small>
                                <span class="badge mt-1 {{ $team->championships_count > 0 ? 'bg-warning text-dark' : 'bg-danger' }}">
                                    <i class="bi bi-trophy"></i> {{ $team->championships_count }} {{ $team->championships_count > 1 ? __('messages.titles') : __('messages.title') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-people text-muted" style="font-size:4rem;"></i>
                <p class="mt-3 text-muted">{{ __('messages.no_teams_sync') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
