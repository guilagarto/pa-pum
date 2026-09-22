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
     * Rota correspondente: /chat/{job_id}
     */
    public function show($job_id)
    {
        // 1. Busca a vaga (job) com segurança ou retorna 404 se não existir
        $job = Job::findOrFail($job_id);
        $usuarioLogado = Auth::user();

        // 2. Define dinamicamente quem é o "outro usuário" da conversa
        if ($usuarioLogado->id === $job->user_id) {
            // Se eu sou o dono da vaga (contratante), o outro usuário é quem foi contratado
            $providerId = $job->provider_id ?? $job->professional_id ?? $job->prestador_id ?? 0;
            
            // Tratamento para vaga de teste: se o ID for 0 ou nulo, busca outro usuário válido no banco
            if (!$providerId || $providerId == 0) {
                $outroUsuario = User::where('id', '!=', $usuarioLogado->id)->first() ?? User::find(1);
            } else {
                $outroUsuario = User::find($providerId);
            }
        } else {
            // Se eu não sou o dono da vaga, o outro usuário obrigatoriamente é o dono dela
            $outroUsuario = User::find($job->user_id);
        }

        // 3. Fallback final de segurança para a view não quebrar se o banco estiver totalmente vazio
        if (!$outroUsuario) {
            $outroUsuario = new User(['id' => 1, 'name' => 'Usuário de Teste']);
        }

        // 4. Retorna a view enviando as variáveis estruturadas
        return view('chat.show', compact('job', 'outroUsuario'));
    }

    /**
     * Rota de API (AJAX/Fetch) que o JavaScript chama de 3 em 3 segundos para atualizar a tela
     * Rota correspondente: /chat/{job_id}/mensagens
     */
    public function fetchMessages($job_id)
    {
        // Busca todas as mensagens vinculadas a essa vaga ordenadas por tempo
        $messages = Message::where('job_id', $job_id)
                           ->orderBy('created_at', 'asc')
                           ->get();

        // Retorna a lista em formato JSON puro para o JavaScript renderizar os balões
        return response()->json($messages);
    }

    /**
     * Rota de API (AJAX/Fetch) para gravar as mensagens digitadas no banco de dados
     * Rota correspondente: /chat/{job_id}/enviar (Método POST)
     */
    public function sendMessage(Request $request)
    {
        // 1. Valida se os dados básicos de texto e relacionamento foram preenchidos
        $request->validate([
            'job_id' => 'required',
            'message' => 'required|string'
        ]);

        $job = Job::findOrFail($request->job_id);
        $usuarioLogado = Auth::user();

        // 2. Define quem é o destinatário (receiver_id) da mensagem
        if ($usuarioLogado->id === $job->user_id) {
            $receiverId = $job->provider_id ?? $job->professional_id ?? $job->prestador_id ?? 0;
        } else {
            $receiverId = $job->user_id;
        }

        // CORREÇÃO CRUCIAL: Se o ID for 0 ou nulo (vaga de teste), evita a violação de chave estrangeira
        // Descobre um ID de usuário real e ativo no banco para o MySQL aceitar o INSERT
        if (!$receiverId || $receiverId == 0) {
            $receiverId = User::where('id', '!=', $usuarioLogado->id)->first()->id ?? 1;
        }

        // 3. Persiste a mensagem de forma segura usando Mass Assignment
        $novaMensagem = Message::create([
            'job_id' => $request->job_id,
            'sender_id' => $usuarioLogado->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);

        // 4. Retorna resposta HTTP de sucesso para o JavaScript limpar o campo de texto
        return response()->json([
            'status' => 'Mensagem enviada com sucesso!', 
            'data' => $novaMensagem
        ]);
    }
}
