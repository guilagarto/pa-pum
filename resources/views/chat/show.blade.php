<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat com {{ \$outroUsuario->name }} - Pá-pum</title>
    
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .chat-header {
            background: #ffffff;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .chat-header h2 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }

        .chat-header .btn-voltar {
            color: #4f46e5;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #f9fafb;
        }

        .bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 15px;
            line-height: 1.4;
            word-wrap: break-word;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .bubble.me {
            background-color: #10b981;
            color: #ffffff;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        .bubble.other {
            background-color: #ffffff;
            color: #1f2937;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            border: 1px solid #e5e7eb;
        }

        .chat-footer {
            background: #ffffff;
            padding: 15px 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-footer input {
            flex: 1;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .btn-enviar {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <header class="chat-header">
        <div>
            <h2>Conversa com: <strong>{{ \$outroUsuario->name }}</strong></h2>
            <small style="color: #6b7280;">Serviço: {{ \$job->title }}</small>
        </div>
        <a href="{{ route('profile.custom.edit') }}" class="btn-voltar">← Sair do Chat</a>
    </header>

    <main class="chat-messages" id="chat-box"></main>

    <footer class="chat-footer">
        <input type="text" id="msg-input" placeholder="Digite uma mensagem..." autocomplete="off">
        <button type="button" id="btn-send" class="btn-enviar">Enviar</button>
    </footer>

    <script>
        const jobId = "{{ \$job->id }}";
        const meuId = {{ Auth::id() }};
        const chatBox = document.getElementById('chat-box');
        const msgInput = document.getElementById('msg-input');
        const btnSend = document.getElementById('btn-send');

        async function carregarMensagens() {
            try {
                const response = await fetch(`/chat/${jobId}/mensagens`);
                if (!response.ok) return;
                
                const mensagens = await response.json();
                let html = '';

                mensagens.forEach(msg => {
                    const classeDono = (msg.sender_id === meuId) ? 'me' : 'other';
                    html += `<div class="bubble ${classeDono}">${msg.message}</div>`;
                });

                if (chatBox.innerHTML !== html) {
                    chatBox.innerHTML = html;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            } catch (error) {
                console.error("Erro ao carregar mensagens:", error);
            }
        }

        async function enviarMensagem() {
            const texto = msgInput.value.trim();
            if (!texto) return;

            msgInput.value = '';

            try {
                await fetch(`/chat/${jobId}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: texto })
                });
                carregarMensagens();
            } catch (error) {
                console.error("Erro ao enviar mensagem:", error);
            }
        }

        btnSend.addEventListener('click', enviarMensagem);
        msgInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') enviarMensagem();
        });

        carregarMensagens();
        setInterval(carregarMensagens, 3000);
    </script>

</body>
</html>
