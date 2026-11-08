@extends('layouts.app')

@section('title', 'Gerenciar Jogadores')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.admin') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.players') }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="mb-0"><i class="bi bi-people"></i> Jogadores</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao painel
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="q" class="form-label">Buscar</label>
                    <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}"
                           placeholder="Nome do jogador">
                </div>
                <div class="col-md-3">
                    <label for="team" class="form-label">Time</label>
                    <select name="team" id="team" class="form-select">
                        <option value="">Todos</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->abbreviation }}" @selected(request('team') === $team->abbreviation)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="active"
                               @checked(request('active') === '1')>
                        <label class="form-check-label" for="active">Somente ativos</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-muted small">{{ $players->total() }} jogadores encontrados</p>

    <div class="table-responsive">
        <table class="table table-sm align-middle bg-white">
            <thead>
                <tr>
                    <th style="width:64px;"></th>
                    <th>Jogador</th>
                    <th>Time</th>
                    <th>Pos</th>
                    <th>#</th>
                    <th>Altura</th>
                    <th>Peso</th>
                    <th>Nascimento</th>
                    <th>Status</th>
                    <th style="width:90px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($players as $player)
                    <tr>
                        <td>
                            @if($player->photo)
                                <img src="{{ $player->photo }}" alt="{{ $player->full_name }}"
                                     style="width:40px;height:40px;object-fit:contain;border-radius:50%;">
                            @else
                                <span class="badge bg-secondary rounded-circle">{{ $player->initials }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.players.edit', $player) }}" class="text-decoration-none">
                                {{ $player->full_name }}
                            </a>
                            @if($player->api_id || $player->apisports_id)
                                <div class="text-muted small">
                                    bdl: {{ $player->api_id ?? '-' }} &middot; api: {{ $player->apisports_id ?? '-' }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $player->team?->abbreviation ?? '-' }}</td>
                        <td>{{ $player->position ?? '-' }}</td>
                        <td>{{ $player->jersey_number ?? '-' }}</td>
                        <td>{{ $player->height ?? '-' }}</td>
                        <td>{{ $player->weight_kg ? $player->weight_kg . 'kg' : ($player->weight ? $player->weight . 'lb' : '-') }}</td>
                        <td>{{ $player->birth_date?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $player->is_active ? 'success' : 'warning text-dark' }}">
                                {{ $player->is_active ? 'Ativo' : 'Histórico' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.players.edit', $player) }}"
                               class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Nenhum jogador encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $players->links() }}
</div>
@endsection