@extends('layouts.app')

@section('title', $team->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $team->logo }}" alt="{{ $team->name }}" class="mb-3" style="width:120px;">
                    <h2>{{ $team->name }}</h2>
                    <p class="text-muted">{{ $team->city }} • {{ $team->conference }}ern Conference</p>

                    <div class="row mt-4">
                        <div class="col-4">
                            <div class="h3 mb-0">{{ $gamesWon }}</div>
                            <small class="text-muted">{{ __('messages.wins') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0">{{ $gamesTotal - $gamesWon }}</div>
                            <small class="text-muted">{{ __('messages.losses') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0">{{ $winRate }}%</div>
                            <small class="text-muted">{{ __('messages.win_rate') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Divisão:</strong> {{ $team->division }}</li>
                        <li class="mb-2"><strong>Arena:</strong> {{ $team->arena_name ?? '-' }}</li>
                        <li class="mb-2"><strong>Capacidade:</strong> {{ $team->arena_capacity ? number_format($team->arena_capacity) : '-' }}</li>
                        <li class="mb-2"><strong>Fundação:</strong> {{ $team->founded_year ?? '-' }}</li>
                        <li><strong>Títulos:</strong> {{ $team->championships_count }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if($avgStats)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.statistics') }} (Média por Jogo)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-primary">{{ number_format($avgStats->avg_points, 1) }}</div>
                                <small>{{ __('messages.points_per_game') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-success">{{ number_format($avgStats->avg_rebounds, 1) }}</div>
                                <small>{{ __('messages.rebounds_per_game') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-info">{{ number_format($avgStats->avg_assists, 1) }}</div>
                                <small>{{ __('messages.assists_per_game') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.current_roster') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($currentPlayers as $player)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $player->photo }}" alt="{{ $player->full_name }}" class="rounded-circle me-3" style="width:50px;height:50px;object-fit:cover;">
                                    <div>
                                        <a href="{{ route('stats.player', $player) }}" class="text-decoration-none fw-bold">{{ $player->full_name }}</a>
                                        <br>
                                        <small class="text-muted">{{ $player->position }} • #{{ $player->jersey_number ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">{{ __('messages.no_results') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($formerPlayers->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.former_players') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.player') }}</th>
                                        <th>Período</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formerPlayers as $player)
                                        <tr>
                                            <td>
                                                <a href="{{ route('stats.player', $player) }}">{{ $player->full_name }}</a>
                                            </td>
                                            <td>
                                                {{ $player->teamHistory->first()?->start_date->format('Y') }} -
                                                {{ $player->teamHistory->first()?->end_date?->format('Y') ?? 'Presente' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection