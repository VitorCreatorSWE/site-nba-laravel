@extends('layouts.app')

@section('title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        @if($game->isLive())
                            <span class="badge bg-danger fs-6"><i class="bi bi-broadcast"></i> {{ __('messages.live') }}</span>
                        @elseif($game->isFinished())
                            <span class="badge bg-secondary fs-6">{{ __('messages.finished') }}</span>
                        @else
                            <span class="badge bg-info fs-6">{{ __('messages.scheduled') }}</span>
                        @endif
                        <span class="text-muted">{{ $game->game_date->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="row align-items-center text-center">
                        <div class="col-5">
                            <img src="{{ $game->homeTeam->logo }}" alt="{{ $game->homeTeam->name }}" class="team-logo mb-3" style="width:100px;height:100px;">
                            <h4>{{ $game->homeTeam->name }}</h4>
                            @if($game->isLive() || $game->isFinished())
                                <div class="display-4 fw-bold">{{ $game->home_score }}</div>
                            @endif
                        </div>
                        <div class="col-2">
                            <span class="badge bg-dark fs-5">VS</span>
                        </div>
                        <div class="col-5">
                            <img src="{{ $game->awayTeam->logo }}" alt="{{ $game->awayTeam->name }}" class="team-logo mb-3" style="width:100px;height:100px;">
                            <h4>{{ $game->awayTeam->name }}</h4>
                            @if($game->isLive() || $game->isFinished())
                                <div class="display-4 fw-bold">{{ $game->away_score }}</div>
                            @endif
                        </div>
                    </div>

                    @if($game->arena)
                        <div class="text-center mt-4">
                            <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $game->arena }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($game->stats->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.statistics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.statistics') }}</th>
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
                                        <td>{{ __('messages.points_per_game') }}</td>
                                        <td class="text-center fw-bold">{{ $homeStats->points ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ $awayStats->points ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __("messages.rebounds") }}</td>
                                        <td class="text-center">{{ $homeStats->rebounds ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->rebounds ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>AssistÃªncias</td>
                                        <td class="text-center">{{ $homeStats->assists ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->assists ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __("messages.steals") }}</td>
                                        <td class="text-center">{{ $homeStats->steals ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->steals ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __("messages.blocks") }}</td>
                                        <td class="text-center">{{ $homeStats->blocks ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->blocks ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __("messages.turnovers") }}</td>
                                        <td class="text-center">{{ $homeStats->turnovers ?? '-' }}</td>
                                        <td class="text-center">{{ $awayStats->turnovers ?? '-' }}</td>
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
                    <img src="{{ $game->homeTeam->logo }}" class="mb-3" style="width:80px;" alt="">
                    <p><strong>ConferÃªncia:</strong> {{ $game->homeTeam->conference }}</p>
                    <p><strong>DivisÃ£o:</strong> {{ $game->homeTeam->division }}</p>
                    <p><strong>Arena:</strong> {{ $game->homeTeam->arena_name ?? '-' }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $game->awayTeam->name }}</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $game->awayTeam->logo }}" class="mb-3" style="width:80px;" alt="">
                    <p><strong>ConferÃªncia:</strong> {{ $game->awayTeam->conference }}</p>
                    <p><strong>DivisÃ£o:</strong> {{ $game->awayTeam->division }}</p>
                    <p><strong>Arena:</strong> {{ $game->awayTeam->arena_name ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
