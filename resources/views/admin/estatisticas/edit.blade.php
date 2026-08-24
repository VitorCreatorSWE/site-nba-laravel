@extends('layouts.app')

@section('title', 'Editar Estatística')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">📊 Editar Estatística</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.posts.update', $post) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cover_image" class="form-label">URL da Imagem de Capa</label>
                                <input type="url" name="cover_image" id="cover_image" class="form-control" value="{{ old('cover_image', $post->cover_image) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Resumo</label>
                            <input type="text" name="excerpt" id="excerpt" class="form-control" value="{{ old('excerpt', $post->excerpt) }}" maxlength="500">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo *</label>
                            <textarea name="content" id="content" class="form-control" rows="15" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Salvar
                            </button>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection