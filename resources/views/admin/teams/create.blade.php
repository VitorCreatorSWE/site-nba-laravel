@extends('layouts.app')

@section('title', isset($team) ? 'Editar Time' : 'Criar Time')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">
                <i class="bi bi-shield-shaded"></i>
                {{ isset($team) ? 'Editar Time' : 'Criar Time' }}
            </h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ isset($team) ? route('admin.teams.update', $team) : route('admin.teams.store') }}">
                        @csrf
                        @if(isset($team))
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $team->name ?? '') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="abbreviation" class="form-label">Abreviação *</label>
                                <input type="text" name="abbreviation" id="abbreviation" class="form-control"
                                    value="{{ old('abbreviation', $team->abbreviation ?? '') }}" maxlength="10" required>
                            </div>
                            <div class="col-md-3">
                                <label for="api_id" class="form-label">API ID *</label>
                                <input type="number" name="api_id" id="api_id" class="form-control"
                                    value="{{ old('api_id', $team->api_id ?? '') }}" {{ isset($team) ? 'disabled' : '' }} required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">Cidade *</label>
                                <input type="text" name="city" id="city" class="form-control"
                                    value="{{ old('city', $team->city ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" name="state" id="state" class="form-control"
                                    value="{{ old('state', $team->state ?? '') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="conference" class="form-label">Conferência *</label>
                                <select name="conference" id="conference" class="form-select" required>
                                    <option value="East" {{ old('conference', $team->conference ?? '') === 'East' ? 'selected' : '' }}>East</option>
                                    <option value="West" {{ old('conference', $team->conference ?? '') === 'West' ? 'selected' : '' }}>West</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="division" class="form-label">Divisão *</label>
                                <input type="text" name="division" id="division" class="form-control"
                                    value="{{ old('division', $team->division ?? '') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="arena_name" class="form-label">Arena</label>
                                <input type="text" name="arena_name" id="arena_name" class="form-control"
                                    value="{{ old('arena_name', $team->arena_name ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="arena_capacity" class="form-label">Capacidade</label>
                                <input type="number" name="arena_capacity" id="arena_capacity" class="form-control"
                                    value="{{ old('arena_capacity', $team->arena_capacity ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="championships_count" class="form-label">Títulos</label>
                                <input type="number" name="championships_count" id="championships_count" class="form-control"
                                    value="{{ old('championships_count', $team->championships_count ?? 0) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="logo_url" class="form-label">URL do Logo</label>
                                <input type="url" name="logo_url" id="logo_url" class="form-control"
                                    value="{{ old('logo_url', $team->logo_url ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="founded_year" class="form-label">Ano de Fundação</label>
                                <input type="number" name="founded_year" id="founded_year" class="form-control"
                                    value="{{ old('founded_year', $team->founded_year ?? '') }}" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> {{ __('messages.save') }}
                            </button>
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection