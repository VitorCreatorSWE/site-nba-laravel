@extends('layouts.app')

@section('title', 'Admin - Jogos')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar3"></i> Gerenciar Jogos</h1>
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Criar Jogo
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Mandante</th>
                            <th>Visitante</th>
                            <th>Placar</th>
                            <th>Temporada</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                            <tr>
                                <td>{{ $game->game_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $game->homeTeam->logo }}" alt="" style="width:25px;" class="me-2">
                                        {{ $game->homeTeam->name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $game->awayTeam->logo }}" alt="" style="width:25px;" class="me-2">
                                        {{ $game->awayTeam->name }}
                                    </div>
                                </td>
                                <td>
                                    @if($game->isScheduled())
                                        <span class="text-muted">-</span>
                                    @else
                                        <strong>{{ $game->home_score }} x {{ $game->away_score }}</strong>
                                    @endif
                                </td>
                                <td>{{ $game->season ?? '-' }}</td>
                                <td>
                                    @if($game->isLive())
                                        <span class="badge bg-danger"><i class="bi bi-broadcast"></i> Ao Vivo</span>
                                    @elseif($game->isFinished())
                                        <span class="badge bg-secondary">Finalizado</span>
                                    @else
                                        <span class="badge bg-info">Agendado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.games.destroy', $game) }}" class="d-inline"
                                        onsubmit="return confirm('Tem certeza?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Nenhum jogo encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $games->links() }}
    </div>
</div>
@endsection