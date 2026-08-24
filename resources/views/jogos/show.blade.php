@extends('layouts.app')

@section('title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name . ' - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('jogos.index') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Voltar para Jogos
                </a>
            </div>

            <div class="card mb-4 shadow">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        @if($game->isLive())
                            <span class="badge bg-danger fs-6"><i class="bi bi-broadcast"></i> AO VIVO</span>
                        @elseif($game->isFinished())
                            <span class="badge bg-secondary fs-6">Finalizado</span>
                        @else
                            <span class="badge bg-info fs-6">Agendado</span>
                        @endif
                        <span class="text-muted"><i class="bi bi-calendar"></i> {{ $game->game_date->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="row align-items-center text-center">
                        <div class="col-5">
                            <a href="{{ route('estatisticas.team', $game->homeTeam) }}" class="text-decoration-none">
                                <img src="{{ $game->homeTeam->logo }}" alt="{{ $game->homeTeam->name }}" class="mb-3" style="width:120px;">
                                <h4 class="text-dark">{{ $game->homeTeam->name }}</h4>
                            </a>
                            @if($game->isLive() || $game->isFinished())
                                <div class="display-3 fw-bold text-primary">{{ $game->home_score }}</div>
                            @endif
                        </div>
                        <div class="col-2">
                            <span class="badge bg-dark fs-4">VS</span>
                            @if($game->isFinished())
                                <div class="mt-2">
                                    @if($game->home_score > $game->away_score)
                                        <span class="badge bg-success">Vitória Mandante</span>
                                    @elseif($game->away_score > $game->home_score)
                                        <span class="badge bg-success">Vitória Visitante</span>
                                    @else
                                        <span class="badge bg-warning">Empate</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="col-5">
                            <a href="{{ route('estatisticas.team', $game->awayTeam) }}" class="text-decoration-none">
                                <img src="{{ $game->awayTeam->logo }}" alt="{{ $game->awayTeam->name }}" class="mb-3" style="width:120px;">
                                <h4 class="text-dark">{{ $game->awayTeam->name }}</h4>
                            </a>
                            @if($game->isLive() || $game->isFinished())
                                <div class="display-3 fw-bold text-primary">{{ $game->away_score }}</div>
                            @endif
                        </div>
                    </div>

                    @if($game->arena)
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0"><i class="bi bi-geo-alt"></i> {{ $game->arena }}</p>
                        </div>
                    @endif
                    @if($game->season)
                        <div class="text-center">
                            <small class="text-muted">Temporada: {{ $game->season }}</small>
                        </div>
                    @endif
                </div>
            </div>

            @if($game->admin_analysis)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Análise do Jogo</h5>
                    </div>
                    <div class="card-body">
                        <div class="content">{!! nl2br(e($game->admin_analysis)) !!}</div>
                    </div>
                </div>
            @endif

            @if(isset($analysis))
                <div class="card mt-4 shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-journal-text"></i> Análise do Jogo</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $gameHour = (int) date('H', strtotime($game->game_date));
                            $periodLabel = $gameHour < 17 ? 'Tarde' : 'Noite';
                        @endphp
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-{{ $gameHour < 17 ? 'sun' : 'moon-fill' }}"></i>
                                {{ $periodLabel }}
                            </small>
                        </div>
                        <p style="text-align:justify;">{{ $analysis }}</p>
                    </div>
                </div>
            @endif

            @if($game->stats->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estatísticas do Jogo</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Estatística</th>
                                        <th class="text-center">{{ $game->homeTeam->abbreviation }}</th>
                                        <th class="text-center">{{ $game->awayTeam->abbreviation }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $homeStats = $game->stats->where('team_id', $game->home_team_id)->first();
                                        $awayStats = $game->stats->where('team_id', $game->away_team_id)->first();
                                    @endphp
                                    <tr>
                                        <td><strong>Pontos</strong></td>
                                        <td class="text-center fw-bold fs-5">{{ $homeStats->points ?? '-' }}</td>
                                        <td class="text-center fw-bold fs-5">{{ $awayStats->points ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Rebotes</td>
                                        <td class="text-center">{{ $homeStats->rebounds ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->rebounds ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Assistências</td>
                                        <td class="text-center">{{ $homeStats->assists ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->assists ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Roubos de Bola</td>
                                        <td class="text-center">{{ $homeStats->steals ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->steals ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bloqueios</td>
                                        <td class="text-center">{{ $homeStats->blocks ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->blocks ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Perdas de Bola</td>
                                        <td class="text-center">{{ $homeStats->turnovers ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->turnovers ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Cestos de 2</td>
                                        <td class="text-center">{{ $homeStats->field_goals_made ?? '-' }} / {{ $homeStats->field_goals_attempted ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->field_goals_made ?? '-' }} / {{ $awayStats->field_goals_attempted ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Cestos de 3</td>
                                        <td class="text-center">{{ $homeStats->three_pointers_made ?? '-' }} / {{ $homeStats->three_pointers_attempted ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->three_pointers_made ?? '-' }} / {{ $awayStats->three_pointers_attempted ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Lances Livres</td>
                                        <td class="text-center">{{ $homeStats->free_throws_made ?? '-' }} / {{ $homeStats->free_throws_attempted ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->free_throws_made ?? '-' }} / {{ $awayStats->free_throws_attempted ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $game->homeTeam->name }}</h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('estatisticas.team', $game->homeTeam) }}">
                        <img src="{{ $game->homeTeam->logo }}" class="mb-3" style="width:80px;" alt="">
                    </a>
                    <p><strong>Conferência:</strong> {{ $game->homeTeam->conference }}</p>
                    <p><strong>Divisão:</strong> {{ $game->homeTeam->division }}</p>
                    <p><strong>Arena:</strong> {{ $game->homeTeam->arena_name ?? '-' }}</p>
                    <a href="{{ route('estatisticas.team', $game->homeTeam) }}" class="btn btn-outline-primary btn-sm">
                        Ver Estatísticas
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $game->awayTeam->name }}</h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('estatisticas.team', $game->awayTeam) }}">
                        <img src="{{ $game->awayTeam->logo }}" class="mb-3" style="width:80px;" alt="">
                    </a>
                    <p><strong>Conferência:</strong> {{ $game->awayTeam->conference }}</p>
                    <p><strong>Divisão:</strong> {{ $game->awayTeam->division }}</p>
                    <p><strong>Arena:</strong> {{ $game->awayTeam->arena_name ?? '-' }}</p>
                    <a href="{{ route('estatisticas.team', $game->awayTeam) }}" class="btn btn-outline-primary btn-sm">
                        Ver Estatísticas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection