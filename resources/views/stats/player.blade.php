@extends('layouts.app')

@section('title', $player->full_name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $player->photo }}" alt="{{ $player->full_name }}" class="rounded-circle mb-3" style="width:150px;height:150px;object-fit:cover;">
                    <h2>{{ $player->full_name }}</h2>
                    @if($player->nickname)
                        <p class="text-muted">"{{ $player->nickname }}"</p>
                    @endif

                    <div class="mb-3">
                        <span class="badge bg-primary fs-6">#{{ $player->jersey_number ?? '-' }}</span>
                        <span class="badge bg-secondary fs-6">{{ $player->position }}</span>
                    </div>

                    @if($player->team)
                        <div class="mt-3">
                            <img src="{{ $player->team->logo }}" alt="{{ $player->team->name }}" style="width:60px;">
                            <p class="mt-2 mb-0 fw-bold">{{ $player->team->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações Pessoais</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Altura:</strong> {{ $player->height_formatted }}</li>
                        <li class="mb-2"><strong>Peso:</strong> {{ $player->weight_formatted }}</li>
                        @if($player->wingspan_cm)
                            <li class="mb-2"><strong>Envergadura:</strong> {{ number_format($player->wingspan_cm, 1) }} cm</li>
                        @endif
                        <li class="mb-2"><strong>Nascimento:</strong> {{ $player->birth_date?->format('d/m/Y') ?? '-' }}</li>
                        <li><strong>Nacionalidade:</strong> {{ $player->nationality ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if($player->careerStats)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.career_stats') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-primary">{{ $player->careerStats->games_played }}</div>
                                <small>Jogos</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-danger">{{ number_format($player->careerStats->points_per_game, 1) }}</div>
                                <small>{{ __('messages.points_per_game') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-success">{{ number_format($player->careerStats->rebounds_per_game, 1) }}</div>
                                <small>{{ __('messages.rebounds_per_game') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-info">{{ number_format($player->careerStats->assists_per_game, 1) }}</div>
                                <small>{{ __('messages.assists_per_game') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-warning">{{ number_format($player->careerStats->field_goal_percentage, 1) }}%</div>
                                <small>FG%</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="h3 text-secondary">{{ number_format($player->careerStats->three_point_percentage, 1) }}%</div>
                                <small>3P%</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($player->seasonStats->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.season_stats') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Temporada</th>
                                        <th class="text-center">Jogos</th>
                                        <th class="text-center">PPG</th>
                                        <th class="text-center">RPG</th>
                                        <th class="text-center">APG</th>
                                        <th class="text-center">FG%</th>
                                        <th class="text-center">3P%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($player->seasonStats->sortByDesc('season') as $stat)
                                        <tr>
                                            <td>{{ $stat->season }}</td>
                                            <td class="text-center">{{ $stat->games_played }}</td>
                                            <td class="text-center">{{ number_format($stat->points_per_game, 1) }}</td>
                                            <td class="text-center">{{ number_format($stat->rebounds_per_game, 1) }}</td>
                                            <td class="text-center">{{ number_format($stat->assists_per_game, 1) }}</td>
                                            <td class="text-center">{{ number_format($stat->field_goal_percentage, 1) }}%</td>
                                            <td class="text-center">{{ number_format($stat->three_point_percentage, 1) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($player->teamHistory->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Histórico de Times</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Período</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($player->teamHistory->sortByDesc('start_date') as $history)
                                        <tr>
                                            <td>
                                                <img src="{{ $history->team->logo }}" alt="" style="width:25px;" class="me-2">
                                                {{ $history->team->name }}
                                            </td>
                                            <td>
                                                {{ $history->start_date->format('Y') }} -
                                                {{ $history->end_date?->format('Y') ?? 'Presente' }}
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