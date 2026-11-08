@extends('layouts.app')

@section('title', 'Gerenciar Times')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-shield-shaded"></i> Gerenciar Times</h1>
        <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> {{ __('messages.create') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Abreviação</th>
                            <th>Conferência</th>
                            <th>Divisão</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->abbreviation }}</td>
                                <td><span class="badge bg-{{ $team->conference === 'East' ? 'primary' : 'danger' }}">{{ $team->conference }}</span></td>
                                <td>{{ $team->division }}</td>
                                <td>
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="d-inline"
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
                                <td colspan="5" class="text-center text-muted">{{ __('messages.no_results') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $teams->links() }}
    </div>
</div>
@endsection