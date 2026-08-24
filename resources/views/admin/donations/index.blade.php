@extends('layouts.app')

@section('title', 'Gerenciar Doações')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="bi bi-heart-fill text-danger"></i> Gerenciar Doações</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="h3">R$ {{ number_format($stats['total'], 2, ',', '.') }}</div>
                    <p class="mb-0">Total Confirmado</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['pending'] }}</div>
                    <p class="mb-0">Pendentes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['confirmed'] }}</div>
                    <p class="mb-0">Confirmadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['failed'] }}</div>
                    <p class="mb-0">Rejeitadas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Doador</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td>{{ $donation->transaction_id }}</td>
                                <td>{{ $donation->donor_name ?? 'Anônimo' }}</td>
                                <td>{{ $donation->amount_formatted }}</td>
                                <td>
                                    <span class="badge bg-{{ $donation->status === 'confirmed' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ $donation->status }}
                                    </span>
                                </td>
                                <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($donation->isPending())
                                        <form method="POST" action="{{ route('admin.donations.confirm', $donation) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.donations.reject', $donation) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    @endif
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
        {{ $donations->links() }}
    </div>
</div>
@endsection