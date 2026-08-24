@extends('layouts.app')

@section('title', 'Editar Time')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4"><i class="bi bi-shield-shaded"></i> Editar Time</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.teams.update', $team) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $team->name) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="abbreviation" class="form-label">Abreviação *</label>
                                <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ old('abbreviation', $team->abbreviation) }}" maxlength="10" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">Cidade *</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $team->city) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="conference" class="form-label">Conferência *</label>
                                <select name="conference" id="conference" class="form-select" required>
                                    <option value="East" {{ old('conference', $team->conference) === 'East' ? 'selected' : '' }}>East</option>
                                    <option value="West" {{ old('conference', $team->conference) === 'West' ? 'selected' : '' }}>West</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="division" class="form-label">Divisão *</label>
                                <input type="text" name="division" id="division" class="form-control" value="{{ old('division', $team->division) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="arena_name" class="form-label">Arena</label>
                                <input type="text" name="arena_name" id="arena_name" class="form-control" value="{{ old('arena_name', $team->arena_name) }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> {{ __('messages.save') }}</button>
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection