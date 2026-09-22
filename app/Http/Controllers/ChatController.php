<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Abre a tela visual do Chat entre o Contratante e o Prestador.
     */
    public function show($job_id)
    {
        // 1. Busca a vaga (job) com segurança
        $job = Job::findOrFail($job_id);

        // 2. Descobre quem é o usuário logado atualmente
        $usuarioLogado = Auth::user();

        // 3. Define quem é o "outro usuário" da conversa
        // Se o logado for o dono da vaga (contratante), o outro é o profissional (prestador).
        // Se o logado não for o dono, então ele é o prestador e o outro é o dono da vaga.
        if ($usuarioLogado->id === $job->user_id) {
            // Se a sua tabela Job tiver a coluna do profissional como 'provider_id' ou 'professional_id', ajuste aqui:
            $outroUsuario = User::find($job->provider_id); 
        } else {
            $outroUsuario = User::find($job->user_id);
        }

        // 4. Se por algum motivo o outro usuário não for encontrado, evita o erro criando um objeto vazio temporário
        if (!$outroUsuario) {
            $outroUsuario = new User(['name' => 'Usuário']);
        }

        // 5. Envia as duas variáveis cruciais para a sua View show.blade.php
        return view('chat.show', compact('job', 'outroUsuario'));
    }
    /**
     * Rota de API (AJAX/Fetch) que o JavaScript chama de 3 em 3 segundos para buscar novas mensagens.
     */
       /**
     * Rota de API (AJAX/Fetch) que o JavaScript chama de 3 em 3 segundos para buscar novas mensagens
     */
    public function fetchMessages($job_id)
    {
        // Busca todas as mensagens dessa vaga, incluindo os dados do remetente (sender)
        $messages = Message::where('job_id', $job_id)
                           ->with('sender')
                           ->orderBy('created_at', 'asc')
                           ->get();

        // Retorna as mensagens em formato JSON para o JavaScript renderizar na tela
        return response()->json($messages);
    }

    /**
     * Rota de API (AJAX/Fetch) para salvar uma nova mensagem enviada pelo usuário
     */
    public function sendMessage(Request $request)
    {
        // 1. Valida se a mensagem e o ID da vaga foram enviados
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'message' => 'required|string'
        ]);

        // 2. Busca a vaga para descobrir quem vai receber a mensagem
        $job = Job::findOrFail($request->job_id);
        $usuarioLogado = Auth::user();

        // 3. Define quem é o destinatário (receiver_id)
        if ($usuarioLogado->id === $job->user_id) {
            // Se o logado for o dono da vaga, o destinatário é o prestador
            $receiverId = $job->provider_id; // <-- Ajuste o nome da coluna se necessário
        } else {
            // Se o logado for o prestador, o destinatário é o dono da vaga
            $receiverId = $job->user_id;
        }

        // 4. Cria e salva a mensagem no banco de dados
        $novaMensagem = Message::create([
            'job_id' => $request->job_id,
            'sender_id' => $usuarioLogado->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);

        // 5. Retorna sucesso para o JavaScript limpar o campo de texto
        return response()->json(['status' => 'Mensagem enviada com sucesso!', 'data' => $novaMensagem]);
    }

}
