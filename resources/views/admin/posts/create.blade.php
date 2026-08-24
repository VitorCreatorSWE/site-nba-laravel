@extends('layouts.app')

@section('title', 'Criar Post')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Criar Post</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.posts.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Categoria *</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <input type="radio" class="btn-check" name="category" id="cat-noticia" value="noticia"
                                    {{ old('category', $category ?? 'noticia') === 'noticia' ? 'checked' : '' }} onchange="updateFormFields()">
                                <label class="btn btn-outline-primary" for="cat-noticia"><i class="bi bi-newspaper"></i> Notícia</label>

                                <input type="radio" class="btn-check" name="category" id="cat-recente" value="recente"
                                    {{ old('category', $category ?? '') === 'recente' ? 'checked' : '' }} onchange="updateFormFields()">
                                <label class="btn btn-outline-warning" for="cat-recente"><i class="bi bi-lightning"></i> Notícia Recente</label>

                                <input type="radio" class="btn-check" name="category" id="cat-estatistica" value="estatistica"
                                    {{ old('category', $category ?? '') === 'estatistica' ? 'checked' : '' }} onchange="updateFormFields()">
                                <label class="btn btn-outline-success" for="cat-estatistica"><i class="bi bi-bar-chart"></i> Estatística</label>

                                <input type="radio" class="btn-check" name="category" id="cat-analise" value="analise"
                                    {{ old('category', $category ?? '') === 'analise' ? 'checked' : '' }} onchange="updateFormFields()">
                                <label class="btn btn-outline-info" for="cat-analise"><i class="bi bi-graph-up"></i> Análise</label>
                            </div>
                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg"
                                value="{{ old('title') }}" required placeholder="Título do post...">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cover_image" class="form-label">URL da Imagem de Capa</label>
                                <input type="url" name="cover_image" id="cover_image" class="form-control"
                                    value="{{ old('cover_image') }}" placeholder="https://exemplo.com/imagem.jpg">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3" id="excerpt-group">
                            <label for="excerpt" class="form-label">Resumo</label>
                            <input type="text" name="excerpt" id="excerpt" class="form-control"
                                value="{{ old('excerpt') }}" maxlength="500" placeholder="Breve resumo do post...">
                        </div>

                        <div class="mb-3" id="content-group">
                            <label for="content" class="form-label">Conteúdo *</label>
                            <textarea name="content" id="content" class="form-control" rows="15" required
                                placeholder="Escreva o conteúdo do post aqui...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Salvar Post
                            </button>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary btn-lg">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFormFields() {
    const category = document.querySelector('input[name="category"]:checked').value;
    const contentLabel = document.querySelector('#content-group label');
    const contentPlaceholder = document.querySelector('#content');

    switch(category) {
        case 'noticia':
            contentLabel.textContent = 'Conteúdo da Notícia *';
            contentPlaceholder.placeholder = 'Escreva a notícia completa...';
            break;
        case 'recente':
            contentLabel.textContent = 'Resumo da Notícia *';
            contentPlaceholder.placeholder = 'Escreva um resumo rápido da notícia...';
            break;
        case 'estatistica':
            contentLabel.textContent = 'Dados Estatísticos *';
            contentPlaceholder.placeholder = 'Apresente os dados estatísticos aqui...';
            break;
        case 'analise':
            contentLabel.textContent = 'Análise *';
            contentPlaceholder.placeholder = 'Escreva sua análise detalhada...';
            break;
    }
}

document.addEventListener('DOMContentLoaded', updateFormFields);
</script>
@endpush
@endsection