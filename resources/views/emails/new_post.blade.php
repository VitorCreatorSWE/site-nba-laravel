<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1d428a; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { display: inline-block; background: #c9082a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏀 Basq NBA</h1>
        </div>
        <div class="content">
            <h2>{{ $post->title }}</h2>
            <p><strong>Autor:</strong> {{ $user->name }}</p>
            <p><strong>Categoria:</strong> {{ $post->category_label }}</p>
            @if($post->excerpt)
                <p>{{ $post->excerpt }}</p>
            @endif
            @if($post->category === 'analise')
                <p><a href="{{ route('analises.show', $post) }}" class="button">Ler artigo completo</a></p>
            @elseif($post->category === 'recente')
                <p><a href="{{ route('recentes.show', $post) }}" class="button">Ler artigo completo</a></p>
            @else
                <p><a href="{{ route('noticias.show', $post) }}" class="button">Ler artigo completo</a></p>
            @endif
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Basq NBA. Todos os direitos reservados.</p>
            <p>Para cancelar a inscrição, acesse suas configurações de conta.</p>
        </div>
    </div>
</body>
</html>