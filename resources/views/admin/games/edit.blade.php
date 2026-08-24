@extends('layouts.app')

@section('title', 'Editar Jogo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4"><i class="bi bi-pencil"></i> Editar Jogo</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.games.update', $game) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-5">
                                <label class="form-label">Time Mandante *</label>
                                <input type="text" id="home_team_search" class="form-control" placeholder="Buscar time..."
                                    autocomplete="off" value="{{ $game->homeTeam->name }}" oninput="searchTeam(this, 'home')">
                                <input type="hidden" name="home_team_id" id="home_team_id" value="{{ $game->home_team_id }}">
                                <div id="home_team_results" class="list-group position-absolute z-3"></div>
                                <div class="mt-2">
                                    <div class="alert alert-info py-2 mb-0">
                                        <img src="{{ $game->homeTeam->logo }}" style="width:30px;" class="me-2">
                                        {{ $game->homeTeam->name }}
                                        <small class="text-muted ms-2">{{ $game->homeTeam->arena_name }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end justify-content-center">
                                <span class="badge bg-dark fs-5">VS</span>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Time Visitante *</label>
                                <input type="text" id="away_team_search" class="form-control" placeholder="Buscar time..."
                                    autocomplete="off" value="{{ $game->awayTeam->name }}" oninput="searchTeam(this, 'away')">
                                <input type="hidden" name="away_team_id" id="away_team_id" value="{{ $game->away_team_id }}">
                                <div id="away_team_results" class="list-group position-absolute z-3"></div>
                                <div class="mt-2">
                                    <div class="alert alert-info py-2 mb-0">
                                        <img src="{{ $game->awayTeam->logo }}" style="width:30px;" class="me-2">
                                        {{ $game->awayTeam->name }}
                                        <small class="text-muted ms-2">{{ $game->awayTeam->arena_name }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="game_date" class="form-label">Data/Hora *</label>
                                <input type="datetime-local" name="game_date" id="game_date" class="form-control"
                                    value="{{ old('game_date', $game->game_date->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="arena" class="form-label">Arena/Local</label>
                                <input type="text" name="arena" id="arena" class="form-control"
                                    value="{{ old('arena', $game->arena) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="season" class="form-label">Temporada</label>
                                <input type="text" name="season" id="season" class="form-control"
                                    value="{{ old('season', $game->season) }}" placeholder="2024-2025">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="scheduled" {{ old('status', $game->status) === 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                    <option value="live" {{ old('status', $game->status) === 'live' ? 'selected' : '' }}>Ao Vivo</option>
                                    <option value="finished" {{ old('status', $game->status) === 'finished' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="home_score" class="form-label">Placar Mandante</label>
                                <input type="number" name="home_score" id="home_score" class="form-control"
                                    value="{{ old('home_score', $game->home_score) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="away_score" class="form-label">Placar Visitante</label>
                                <input type="number" name="away_score" id="away_score" class="form-control"
                                    value="{{ old('away_score', $game->away_score) }}" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_analysis" class="form-label">Análise do Admin</label>
                            <textarea name="admin_analysis" id="admin_analysis" class="form-control" rows="5"
                                placeholder="Análise detalhada do jogo...">{{ old('admin_analysis', $game->admin_analysis) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Salvar Alterações
                            </button>
                            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary btn-lg">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout = null;

function searchTeam(input, side) {
    clearTimeout(searchTimeout);
    const query = input.value.trim();
    const resultsDiv = document.getElementById(side + '_team_results');

    if (query.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.api.search-teams') }}?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(teams => {
                resultsDiv.innerHTML = teams.map(team => `
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center"
                        onclick="selectTeam('${side}', ${team.id}, '${team.name}', '${team.arena_name || ''}', '${team.logo}')">
                        <img src="${team.logo}" style="width:30px;" class="me-2">
                        <div>
                            <strong>${team.name}</strong>
                            <br><small class="text-muted">${team.abbreviation} - ${team.city}</small>
                        </div>
                    </button>
                `).join('');
            });
    }, 300);
}

function selectTeam(side, id, name, arena, logo) {
    document.getElementById(side + '_team_id').value = id;
    document.getElementById(side + '_team_search').value = name;
    document.getElementById(side + '_team_results').innerHTML = '';
}
</script>
@endpush
@endsection