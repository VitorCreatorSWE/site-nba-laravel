@extends('layouts.app')

@section('title', 'Criar Jogo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">🏀 Criar Jogo</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.games.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="home_team_id" class="form-label">Time Mandante *</label>
                                <select name="home_team_id" id="home_team_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end justify-content-center">
                                <span class="badge bg-dark fs-6">VS</span>
                            </div>
                            <div class="col-md-5">
                                <label for="away_team_id" class="form-label">Time Visitante *</label>
                                <select name="away_team_id" id="away_team_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="game_date" class="form-label">Data/Hora *</label>
                                <input type="datetime-local" name="game_date" id="game_date" class="form-control"
                                    value="{{ old('game_date') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="arena" class="form-label">Arena/Local</label>
                                <input type="text" name="arena" id="arena" class="form-control" value="{{ old('arena') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="season" class="form-label">Temporada *</label>
                                <input type="text" name="season" id="season" class="form-control" value="{{ old('season', date('Y') . '-' . (date('Y') + 1)) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                    <option value="live" {{ old('status') === 'live' ? 'selected' : '' }}>Ao Vivo</option>
                                    <option value="finished" {{ old('status') === 'finished' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="home_score" class="form-label">Placar Mandante</label>
                                <input type="number" name="home_score" id="home_score" class="form-control" value="{{ old('home_score', 0) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="away_score" class="form-label">Placar Visitante</label>
                                <input type="number" name="away_score" id="away_score" class="form-control" value="{{ old('away_score', 0) }}" min="0">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Salvar
                            </button>
                            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection