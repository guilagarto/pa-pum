<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta tag CRUCIAL para o JavaScript conseguir enviar mensagens no Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat com {{ $outroUsuario->name ?? 'Usuário' }}</title>
    
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background-color: #f3f4f6; }
        .chat-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; flex-direction: column; height: 80vh; }
        .chat-header { padding: 15px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { flex: 1; padding: 15px; overflow-y: auto; background-color: #f9fafb; display: flex; flex-direction: column; gap: 10px; }
        .chat-footer { padding: 15px; border-top: 1px solid #e5e7eb; display: flex; gap: 10px; }
        .msg-input { flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .btn-enviar { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-enviar:hover { background-color: #4338ca; }
        .message-row { display: flex; width: 100%; }
        .message-row.sent { justify-content: flex-end; }
        .message-row.received { justify-content: flex-start; }
        .message-bubble { max-width: 70%; padding: 10px 14px; border-radius: 12px; font-size: 14px; line-height: 1.4; }
        .message-row.sent .message-bubble { background-color: #4f46e5; color: white; border-top-right-radius: 2px; }
        .message-row.received .message-bubble { background-color: #e5e7eb; color: #1f2937; border-top-left-radius: 2px; }
        .btn-voltar { color: #4f46e5; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="chat-container">
    <header class="chat-header">
        <div>
            <h2 style="margin:0; font-size: 18px;">Conversa com: <strong>{{ $outroUsuario->name ?? 'Usuário' }}</strong></h2>
            <small style="color: #6b7280;">Serviço: {{ $job->title ?? 'teste' }}</small>
        </div>
        <a href="/dashboard" class="btn-voltar">← Voltar</a>
    </header>

    <!-- Área onde o JavaScript vai injetar as mensagens dinamicamente -->
    <main class="chat-messages" id="chat-box"></main>

    <!-- Rodapé com o campo de texto estruturado corretamente -->
    <footer class="chat-footer">
        <input type="text" id="msg-input" class="msg-input" placeholder="Digite uma mensagem...">
        <button type="button" id="btn-send" class="btn-enviar">Enviar</button>
    </footer>
</div>

<script>
    // Armazena os dados cruciais vindos do Laravel PHP de forma segura
    const jobId = "{{ $job->id }}";
    const meuId = parseInt("{{ Auth::id() }}");

    const chatBox = document.getElementById('chat-box');
    const msgInput = document.getElementById('msg-input');
    const btnSend = document.getElementById('btn-send');

    // Função assíncrona que busca o histórico de mensagens no backend
    async function carregaMensagens() {
        try {
            const response = await fetch(`/chat/${jobId}/mensagens`);
            if (!response.ok) return;

            const mensagens = await response.json();
            chatBox.innerHTML = ''; // Limpa a tela para recarregar

            mensagens.forEach(msg => {
                const messageRow = document.createElement('div');
                messageRow.classList.add('message-row');
                
                // Define o balão do lado direito (enviado por mim) ou esquerdo (recebido)
                if (parseInt(msg.sender_id) === meuId) {
                    messageRow.classList.add('sent');
                } else {
                    messageRow.classList.add('received');
                }

                messageRow.innerHTML = `<div class="message-bubble">${msg.message}</div>`;
                chatBox.appendChild(messageRow);
            });

            // Rola o chat automaticamente para a última mensagem lá embaixo
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            console.error('Erro ao buscar mensagens:', error);
        }
    }

    // Função que faz o envio do texto digitado para o servidor via AJAX (Fetch API)
    async function enviaMensagem() {
        const texto = msgInput.value.trim();
        if (!texto) return;

        // Pega o token CSRF gerado no meta tag do topo do HTML
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/chat/${jobId}/enviar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    job_id: jobId,
                    message: texto
                })
            });

            if (response.ok) {
                msgInput.value = ''; // Limpa o campo de texto após o envio com sucesso
                carregaMensagens();  // Atualiza a tela imediatamente para mostrar a mensagem nova
            }
        } catch (error) {
            console.error('Erro ao enviar mensagem:', error);
        }
    }

    // Vincula a ação de clique do botão e o pressionar da tecla Enter à função de envio
    btnSend.addEventListener('click', enviaMensagem);
    msgInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') enviaMensagem();
    });

    // Executa a busca inicial de mensagens ao abrir a página
    carregaMensagens();

    // Cria o efeito "Polling": busca novas mensagens no servidor de 3 em 3 segundos automaticamente
    setInterval(carregaMensagens, 3000);
</script>

</body>
</html>
