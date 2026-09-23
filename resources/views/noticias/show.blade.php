<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Blog Pá-pum</title>
    <style>
        /* CSS Otimizado para Leitura no Celular */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; padding: 20px 16px; line-height: 1.6; }
        
        .nav-back { margin-bottom: 20px; }
        .nav-back a { color: #4f46e5; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 4px; }
        
        article { background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        h1 { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin-bottom: 8px; }
        
        .meta-date { font-size: 12px; color: #94a3b8; font-weight: 500; margin-bottom: 20px; display: block; }
        
        .post-content { font-size: 15px; color: #334155; }
        .post-content p { margin-bottom: 16px; }
    </style>
</head>
<body>

    <div class="nav-back">
        <a href="{{ route('noticias.index') }}">← Voltar para Notícias</a>
    </div>

        <article>
        <h1>{{ $post->title }}</h1>
        <span class="meta-date">Publicado em {{ $post->created_at->format('d/m/Y') }}</span>
        
        <!-- BOTÃO DE COMPARTILHAR NO WHATSAPP (Ajustado para Celular) -->
        <div style="margin-bottom: 20px;">
            <a href="https://whatsapp.com{{ urlencode('Olha essa dica legal no Pá-pum: ' . $post->title . ' - ' . request()->url()) }}" 
               target="_blank" 
               style="display: inline-flex; align-items: center; gap: 8px; background-color: #25d366; color: white; text-decoration: none; font-size: 13px; font-weight: bold; padding: 8px 16px; border-radius: 9999px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397 0 12.008 0c3.202.001 6.212 1.249 8.477 3.517 2.266 2.268 3.51 5.28 3.51 8.484-.004 6.657-5.34 12.008-11.953 12.008-1.997-.001-3.957-.5-5.691-1.448L0 24zm6.59-4.846c1.657.985 3.289 1.489 4.954 1.491 5.382 0 9.761-4.385 9.764-9.77.003-2.61-1.012-5.066-2.859-6.916C16.61 2.112 14.16 1.094 11.55 1.094c-5.386 0-9.766 4.387-9.77 9.772-.002 1.889.497 3.324 1.45 4.92l-.995 3.635 3.822-.967z"/>
                </svg>
                Compartilhar no WhatsApp
            </a>
        </div>
        
        <div class="post-content">
            {!! $post->content !!}
        </div>
    </article>


</body>
</html>
