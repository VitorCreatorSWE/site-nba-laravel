@extends('layouts.app')

@section('title', 'Admin - Notícias')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📰 Gerenciar Notícias</h1>
        <a href="{{ route('admin.posts.create', ['category' => 'noticia']) }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Criar Notícia
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ Str::limit($post->title, 50) }}</td>
                                <td>{{ $post->user->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                                    </span>
                                </td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="d-inline"
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
                                <td colspan="5" class="text-center text-muted">Nenhuma notícia encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection