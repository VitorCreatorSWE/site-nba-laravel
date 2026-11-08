@extends('layouts.app')

@section('title', 'Criar Notícia')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">📰 Criar Notícia</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.posts.store') }}">
                        @csrf

                        <input type="hidden" name="category" value="noticia">

                        <div class="mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cover_image" class="form-label">URL da Imagem de Capa</label>
                                <input type="text" name="cover_image" id="cover_image" class="form-control" value="{{ old('cover_image') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Resumo</label>
                            <input type="text" name="excerpt" id="excerpt" class="form-control" value="{{ old('excerpt') }}" maxlength="500">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo *</label>
                            <textarea name="content" id="content" class="form-control" rows="15" required>{{ old('content') }}</textarea>
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