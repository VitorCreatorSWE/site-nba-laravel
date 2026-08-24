@extends('layouts.app')

@section('title', 'Estatísticas - Basq NBA')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="bi bi-bar-chart"></i> Estatísticas</h1>
            <p class="text-muted">Dados e estatísticas de todos os times da NBA</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($teams as $team)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('estatisticas.team', $team) }}" class="text-decoration-none">
                    <div class="card h-100 text-center team-card shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                        <div class="card-body">
                            <img src="{{ $team->logo }}" alt="{{ $team->name }}" class="mb-3" style="width:80px;height:80px;">
                            <h5 class="card-title text-dark">{{ $team->name }}</h5>
                            <p class="text-muted mb-1">{{ $team->city }}</p>
                            <span class="badge bg-{{ $team->conference === 'East' ? 'primary' : 'danger' }}">
                                {{ $team->conference }}ern Conference
                            </span>
                            <p class="mt-2 mb-0">
                                <small class="text-muted">{{ $team->division }} Division</small>
                            </p>
                            @if($team->championships_count > 0)
                                <p class="mt-2 mb-0">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-trophy"></i> {{ $team->championships_count }} {{ $team->championships_count > 1 ? 'Títulos' : 'Título' }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-people text-muted" style="font-size:4rem;"></i>
                <p class="mt-3 text-muted">Nenhum time encontrado. Sincronize os times primeiro.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection