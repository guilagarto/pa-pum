<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Abre a tela visual do Chat entre o Contratante e o Prestador.
     */
    public function show($job_id)
    {
        // Busca o atendimento trazendo os dados do anúncio e participantes
        $job = Job::with(['user', 'review'])->findOrFail($job_id);

        // REGRA DE SEGURANÇA: Só quem participa do atendimento (prestador ou contratante) pode ver o chat
        if (Auth::id() !== $job->user_id && Auth::id() !== $job->contractor_id) {
            abort(403, 'Você não tem permissão para acessar este chat.');
        }

        // Descobre quem é o outro participante da conversa
        $outroUsuario = (Auth::id() === $job->user_id) 
            ? \App\Models\User::find($job->contractor_id) 
            : $job->user;

        // Marca todas as mensagens recebidas neste chat como lidas
        Message::where('job_id', $job->id)
               ->where('receiver_id', Auth::id())
               ->update(['is_read' => true]);

        return view('chat.show', compact('job', 'outroUsuario'));
    }

    /**
     * Rota de API (AJAX/Fetch) que o JavaScript chama de 3 em 3 segundos para buscar novas mensagens.
     */
    public function fetchMessages($job_id)
    {
        $messages = Message::with('sender')
            ->where('job_id', $job_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Retorna as mensagens em formato JSON puro para o JavaScript renderizar na tela
        return response()->json($messages);
    }

    /**
     * Rota de API (AJAX/Fetch) que envia a mensagem em background (sem piscar a tela).
     */
    public function sendMessage(Request $request, $job_id)
    {
        $job = Job::findOrFail($job_id);

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Descobre quem é o destinatário (quem vai receber)
        $receiver_id = (Auth::id() === $job->user_id) ? $job->contractor_id : $job->user_id;

        // Grava a mensagem no banco de dados
        $message = Message::create([
            'job_id' => $job->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_id,
            'message' => $request->message
        ]);

        return response()->json(['status' => 'success', 'message' => $message]);
    }
}
