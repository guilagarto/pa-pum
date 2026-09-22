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

        /* Navbar do Chat */
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

        /* Área de Mensagens */
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #f9fafb;
        }

        /* Balões de Fala */
        .bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 15px;
            line-height: 1.4;
            word-wrap: break-word;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        /* Mensagem Enviada por Mim (Verde) */
        .bubble.me {
            background-color: #10b981;
            color: #ffffff;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        /* Mensagem Recebida do Outro (Branca) */
        .bubble.other {
            background-color: #ffffff;
            color: #1f2937;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            border: 1px solid #e5e7eb;
        }

        /* Rodapé com Campo de Texto */
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

        .chat-footer input:focus {
            border-color: #4f46e5;
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
            transition: background 0.2s;
        }

        .btn-enviar:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho com o nome do contato -->
    <header class="chat-header">
        <div>
            <h2>Conversa com: <strong>{{ \$outroUsuario->name }}</strong></h2>
            <small style="color: #6b7280;">Serviço: {{ \$job->title }}</small>
        </div>
        <a href="{{ route('profile.custom.edit') }}" class="btn-voltar">← Sair do Chat</a>
    </header>

    <!-- Espaço onde as mensagens entram dinamicamente -->
    <main class="chat-messages" id="chat-box">
        <!-- O JavaScript vai preencher este bloco automaticamente -->
    </main>

    <!-- Rodapé com o formulário assíncrono (sem recarregamento) -->
    <footer class="chat-footer">
        <input type="text" id="msg-input" placeholder="Digite uma mensagem..." autocomplete="off">
        <button type="button" id="btn-send" class="btn-enviar">Enviar</button>
    </footer>

    <!-- Script Otimizado de Long Polling para Hospedagem Hostinger -->
    <script>
        const jobId = "{{ \$job->id }}";
        const meuId = {{ Auth::id() }};
        const chatBox = document.getElementById('chat-box');
        const msgInput = document.getElementById('msg-input');
        const btnSend = document.getElementById('btn-send');

        // 1. Função que busca as mensagens do banco de dados (API JSON)
        async function carregarMensagens() {
            try {
                const response = await fetch(`/chat/${jobId}/mensagens`);
                if (!response.ok) return;
                
                const mensagens = await response.json();
                let html = '';

                mensagens.forEach(msg => {
                    // Define se o balão de fala fica na direita (me) ou esquerda (other)
                    const classeDono = (msg.sender_id === meuId) ? 'me' : 'other';
                    html += `<div class="bubble ${classeDono}">${msg.message}</div>`;
                });

                // Atualiza a tela apenas se houver mudança de conteúdo para não dar lag
                if (chatBox.innerHTML !== html) {
                    chatBox.innerHTML = html;
                    // Rola a barra de rolagem automaticamente para a última mensagem
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            } catch (error) {
                console.error("Erro ao carregar mensagens:", error);
            }
        }

        // 2. Função que envia a mensagem em background (AJAX)
        async function enviarMensagem() {
            const texto = msgInput.value.trim();
            if (!texto) return;

            // Limpa o campo de digitação na hora para dar sensação de velocidade instantânea
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
                
                // Recarrega as mensagens imediatamente após o envio
                carregarMensagens();
            } catch (error) {
                console.error("Erro ao enviar mensagem:", error);
            }
        }

        // Eventos de clique no botão e tecla ENTER no teclado
        btnSend.addEventListener('click', enviarMensagem);
        msgInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') enviarMensagem();
        });

        // Inicia buscando as mensagens assim que a tela abre
        carregarMensagens();

        // ⏱️ REGRA DE OURO DO TEMPO REAL LEVE:
        // Executa a busca automática a cada 3 segundos (3000ms) sem pesar o processador da Hostinger
        setInterval(carregarMensagens, 3000);
    </script>

</body>
</html>
