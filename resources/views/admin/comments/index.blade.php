@extends('layouts.app')

@section('title', 'Admin - Comentários')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">💬 Gerenciar Comentários</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Autor</th>
                            <th>Post</th>
                            <th>Comentário</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->user->name }}</td>
                                <td>{{ Str::limit($comment->post->title ?? '-', 30) }}</td>
                                <td>{{ Str::limit($comment->content, 80) }}</td>
                                <td>
                                    @if($comment->approved)
                                        <span class="badge bg-success">Aprovado</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @endif
                                </td>
                                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if(!$comment->approved)
                                        <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Aprovar">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="d-inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este comentário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Nenhum comentário encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection