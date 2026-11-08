@extends('layouts.app')

@section('title', 'Editar Jogador')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.admin') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.players.index') }}">{{ __('messages.players') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $player->full_name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="mb-0"><i class="bi bi-person-badge"></i> {{ $player->full_name }}</h1>
        <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrija os campos abaixo:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.players.update', $player) }}">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Identificação</h5></div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">Nome *</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                               value="{{ old('first_name', $player->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Sobrenome *</label>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                               value="{{ old('last_name', $player->last_name) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="position" class="form-label">Posição</label>
                        <input type="text" name="position" id="position" class="form-control"
                               value="{{ old('position', $player->position) }}" placeholder="G, F, C, G-F...">
                    </div>
                    <div class="col-md-4">
                        <label for="jersey_number" class="form-label">Número da camisa</label>
                        <input type="text" name="jersey_number" id="jersey_number" class="form-control"
                               value="{{ old('jersey_number', $player->jersey_number) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="team_id" class="form-label">Time</label>
                        <select name="team_id" id="team_id" class="form-select">
                            <option value="">Sem time</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" @selected(old('team_id', $player->team_id) == $team->id)>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                           @checked(old('is_active', $player->is_active))>
                    <label class="form-check-label" for="is_active">
                        Jogador ativo (entra no elenco atual exibido no site)
                    </label>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Medidas e nascimento</h5></div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="height" class="form-label">Altura (pés-pol.)</label>
                        <input type="text" name="height" id="height" class="form-control"
                               value="{{ old('height', $player->height) }}" placeholder="6-7">
                    </div>
                    <div class="col-md-3">
                        <label for="height_meters" class="form-label">Altura (m)</label>
                        <input type="number" step="0.01" name="height_meters" id="height_meters" class="form-control"
                               value="{{ old('height_meters', $player->height_meters) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="weight" class="form-label">Peso (lb)</label>
                        <input type="number" name="weight" id="weight" class="form-control"
                               value="{{ old('weight', $player->weight) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="weight_kg" class="form-label">Peso (kg)</label>
                        <input type="number" step="0.1" name="weight_kg" id="weight_kg" class="form-control"
                               value="{{ old('weight_kg', $player->weight_kg) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="birth_date" class="form-label">Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control"
                               value="{{ old('birth_date', $player->birth_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="country" class="form-label">País</label>
                        <input type="text" name="country" id="country" class="form-control"
                               value="{{ old('country', $player->country) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="college" class="form-label">Universidade</label>
                        <input type="text" name="college" id="college" class="form-control"
                               value="{{ old('college', $player->college) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Draft e carreira</h5></div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="draft_year" class="form-label">Ano do draft</label>
                        <input type="number" name="draft_year" id="draft_year" class="form-control"
                               value="{{ old('draft_year', $player->draft_year) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="draft_round" class="form-label">Rodada</label>
                        <input type="number" name="draft_round" id="draft_round" class="form-control"
                               value="{{ old('draft_round', $player->draft_round) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="draft_number" class="form-label">Pick</label>
                        <input type="number" name="draft_number" id="draft_number" class="form-control"
                               value="{{ old('draft_number', $player->draft_number) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="season" class="form-label">Temporada do elenco</label>
                        <input type="number" name="season" id="season" class="form-control"
                               value="{{ old('season', $player->season) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="career_start" class="form-label">Início na NBA</label>
                        <input type="number" name="career_start" id="career_start" class="form-control"
                               value="{{ old('career_start', $player->career_start) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="seasons_played" class="form-label">Temporadas na NBA</label>
                        <input type="number" name="seasons_played" id="seasons_played" class="form-control"
                               value="{{ old('seasons_played', $player->seasons_played) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Dados da API</h5></div>
            <div class="card-body">
                <p class="text-muted small mb-0">
                    <strong>api_id</strong> (balldontlie): {{ $player->api_id ?? '-' }} &nbsp;&middot;&nbsp;
                    <strong>apisports_id</strong>: {{ $player->apisports_id ?? '-' }} &nbsp;&middot;&nbsp;
                    <strong>slug</strong>: <code>{{ $player->slug }}</code>
                </p>
                <p class="text-muted small mb-0 mt-2">
                    Esses identificadores são mantidos pela sincronização e não são editáveis aqui,
                    para não quebrar o vínculo com as APIs.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar alterações</button>
            <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection