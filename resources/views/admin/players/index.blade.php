@extends('layouts.app')

@section('title', 'Gerenciar Jogadores')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge"></i> Gerenciar Jogadores</h1>
        <a href="{{ route('admin.players.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> {{ __('messages.create') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Posição</th>
                            <th>Time</th>
                            <th>#</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($players as $player)
                            <tr>
                                <td><img src="{{ $player->photo }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:50%;"></td>
                                <td>{{ $player->full_name }}</td>
                                <td>{{ $player->position }}</td>
                                <td>{{ $player->team?->name ?? '-' }}</td>
                                <td>{{ $player->jersey_number ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.players.destroy', $player) }}" class="d-inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir?')">
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
                                <td colspan="6" class="text-center text-muted">{{ __('messages.no_results') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $players->links() }}
    </div>
</div>
@endsection