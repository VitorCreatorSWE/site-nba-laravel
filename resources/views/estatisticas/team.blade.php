@extends('layouts.app')

@section('title', $team->name . ' - ' . __('messages.stats') . ' - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="mb-3">
        <a href="{{ route('estatisticas.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> {{ __('messages.back_to_teams') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <img src="{{ $team->logo }}" alt="{{ $team->name }}" class="mb-3" style="width:120px;">
                    <h2>{{ $team->name }}</h2>
                    <p class="text-muted">{{ $team->city }} • {{ $team->conference }}ern Conference</p>

                    <div class="row mt-4">
                        <div class="col-4">
                            <div class="h3 mb-0 text-success">{{ $gamesWon }}</div>
                            <small class="text-muted">{{ __('messages.wins') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0 text-danger">{{ $gamesTotal - $gamesWon }}</div>
                            <small class="text-muted">{{ __('messages.losses') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0 text-primary">{{ $winRate }}%</div>
                            <small class="text-muted">{{ __('messages.win_rate') }}</small>
                        </div>
                    </div>
                    @if($team->championships_count > 0)
                        <div class="mt-3 text-muted">{{ $team->championships_count }} {{ $team->championships_count > 1 ? __('messages.titles') : __('messages.title') }}</div>
                    @else
                        <div class="mt-3 text-danger">0 {{ __('messages.titles') }}</div>
                    @endif
                </div>
            </div>

            <div class="card mt-4 shadow">
                <div class="card-header">
                    <h5 class="mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>{{ __('messages.division') }}:</strong> {{ $team->division }}</li>
                        <li class="mb-2"><strong>{{ __('messages.arena') }}:</strong> {{ $team->arena_name ?? '-' }}</li>
                        <li class="mb-2"><strong>{{ __('messages.capacity') }}:</strong> {{ $team->arena_capacity ? number_format($team->arena_capacity) : '-' }}</li>
                        <li class="mb-2"><strong>{{ __('messages.founded') }}:</strong> {{ $team->founded_year ?? '-' }}</li>
                        <li><strong>{{ __('messages.titles') }}:</strong> {{ $team->championships_count }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if(isset($recentGames) && $recentGames->count() > 0)
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-journal-text"></i> {{ __('messages.recent_game_analysis') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($recentGames as $item)
                            @php
                                $game = $item['game'];
                                $homeWon = $game->home_score > $game->away_score;
                                $winnerTeam = $homeWon ? $game->homeTeam : $game->awayTeam;
                                $loserTeam = $homeWon ? $game->awayTeam : $game->homeTeam;
                                $gameHour = (int) date('H', strtotime($game->game_date));
                                $periodLabel = $gameHour < 17 ? 'Tarde' : 'Noite';
                            @endphp
                            <div class="border-bottom pb-3 mb-3 {{ $loop->last ? 'border-0 mb-0 pb-0' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-{{ $homeWon ? 'success' : 'danger' }}">{{ $homeWon ? 'Vitória' : 'Derrota' }}</span>
                                        <strong class="ms-2">{{ $game->homeTeam->name }} {{ $game->home_score }} - {{ $game->away_score }} {{ $game->awayTeam->name }}</strong>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-{{ $gameHour < 17 ? 'sun' : 'moon-fill' }}"></i>
                                        {{ $periodLabel }} • {{ date('d/m/Y', strtotime($game->game_date)) }}
                                    </small>
                                </div>
                                <p class="text-muted mb-0" style="text-align:justify;">{{ $item['analysis'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
