@extends('layouts.app')

@section('title', 'Editar Jogador')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4"><i class="bi bi-person-badge"></i> Editar Jogador</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.players.update', $player) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="full_name" class="form-label">Nome Completo *</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $player->full_name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="team_id" class="form-label">Time Atual</label>
                                <select name="team_id" id="team_id" class="form-select">
                                    <option value="">Sem time</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="position" class="form-label">Posição *</label>
                                <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $player->position) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="jersey_number" class="form-label">Nº Camisa</label>
                                <input type="text" name="jersey_number" id="jersey_number" class="form-control" value="{{ old('jersey_number', $player->jersey_number) }}" maxlength="5">
                            </div>
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Nacionalidade</label>
                                <input type="text" name="nationality" id="nationality" class="form-control" value="{{ old('nationality', $player->nationality) }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> {{ __('messages.save') }}</button>
                            <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection