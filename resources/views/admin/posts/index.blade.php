@extends('layouts.app')

@section('title', 'Admin - Posts')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-file-earmark-text"></i> Gerenciar Posts</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.posts.create', ['category' => 'noticia']) }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nova Notícia
            </a>
            <a href="{{ route('admin.posts.create', ['category' => 'recente']) }}" class="btn btn-warning">
                <i class="bi bi-plus"></i> Notícia Recente
            </a>
            <a href="{{ route('admin.posts.create', ['category' => 'estatistica']) }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Estatística
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas as categorias</option>
                        <option value="noticia" {{ request('category') === 'noticia' ? 'selected' : '' }}>Notícias</option>
                        <option value="recente" {{ request('category') === 'recente' ? 'selected' : '' }}>Notícias Recentes</option>
                        <option value="estatistica" {{ request('category') === 'estatistica' ? 'selected' : '' }}>Estatísticas</option>
                        <option value="analise" {{ request('category') === 'analise' ? 'selected' : '' }}>Análises</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos os status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoria</th>
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
                                <td>
                                    @php
                                        $catColors = [
                                            'noticia' => 'primary',
                                            'recente' => 'warning',
                                            'estatistica' => 'success',
                                            'analise' => 'info',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $catColors[$post->category] ?? 'secondary' }}">
                                        {{ $post->category_label }}
                                    </span>
                                </td>
                                <td>{{ $post->user->name ?? '-' }}</td>
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
                                <td colspan="6" class="text-center text-muted">Nenhum post encontrado.</td>
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