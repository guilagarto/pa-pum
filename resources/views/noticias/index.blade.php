<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias & Dicas | Pá-pum</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: system-ui, sans-serif; }
        body { background-color: #f8fafc; padding: 16px; color: #1e293b; }
        .header-noticias { margin-bottom: 24px; }
        .header-noticias a { color: #4f46e5; text-decoration: none; font-weight: bold; font-size: 14px; }
        .header-noticias h1 { font-size: 24px; font-weight: 800; margin-top: 8px; }
        .card-post { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.04); margin-bottom: 16px; border: 1px solid #e2e8f0; display: block; text-decoration: none; color: inherit; }
        .post-body { padding: 16px; }
        .post-title { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .post-excerpt { font-size: 13px; color: #64748b; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="header-noticias">
        <a href="/">← Voltar para Home</a>
        <h1>Dicas de Trabalho & Notícias</h1>
    </div>

    @forelse($posts as $post)
        <a href="{{ route('noticias.show', $post->slug) }}" class="card-post">
            <div class="post-body">
                <h2 class="post-title">{{ $post->title }}</h2>
                <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 90) }}</p>
            </div>
        </a>
    @empty
        <p style="color: #64748b; text-align: center;">Nenhuma dica publicada ainda.</p>
    @endforelse
</body>
</html>
