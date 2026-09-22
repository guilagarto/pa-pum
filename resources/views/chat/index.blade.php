<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Conversas</title>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; }
        h1 { font-size: 22px; margin-bottom: 20px; color: #1f2937; }
        .chat-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 10px; text-decoration: none; color: inherit; transition: background 0.2s; }
        .chat-item:hover { background-color: #f9fafb; border-color: #4f46e5; }
        .chat-info h3 { margin: 0; font-size: 16px; color: #1f2937; }
        .chat-info small { color: #6b7280; }
        .btn-abrir { background-color: #4f46e5; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; }
        .no-chats { text-align: center; color: #6b7280; padding: 4px 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>📥 Minhas Conversas</h1>

    @forelse($jobs as $job)
        <a href="{{ route('chat.show', $job->id) }}" class="chat-item">
            <div class="chat-info">
                <h3>Chat com {{ $job->outroUsuario->name }}</h3>
                <small>Serviço: {{ $job->title ?? 'Serviço Contratado' }}</small>
            </div>
            <span class="btn-abrir">Abrir</span>
        </a>
    @empty
        <p class="no-chats">Você ainda não iniciou nenhuma conversa.</p>
    @endforelse
</div>

</body>
</html>
