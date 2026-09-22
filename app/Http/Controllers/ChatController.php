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
     * Lista todas as conversas/vagas ativas do usuário logado.
     * Rota correspondente: /conversas
     */
    public function index()
    {
        $usuarioLogado = Auth::user();

        // Busca todas as vagas onde o usuário logado é o criador (user_id) OU o contratado (contractor_id)
        $jobs = Job::where('user_id', $usuarioLogado->id)
                   ->orWhere('contractor_id', $usuarioLogado->id) 
                   ->orderBy('updated_at', 'desc')
                   ->get();

        // Associa quem é o outro participante de cada conversa para exibir na lista
        foreach ($jobs as $job) {
            if ($usuarioLogado->id === $job->user_id) {
                // Se eu sou o criador da vaga, o outro usuário é o profissional contratado
                $job->outroUsuario = User::find($job->contractor_id) ?? User::where('id', '!=', $usuarioLogado->id)->first() ?? new User(['name' => 'Prestador']);
            } else {
                // Se eu não sou o criador, o outro usuário é o dono da vaga
                $job->outroUsuario = User::find($job->user_id) ?? new User(['name' => 'Contratante']);
            }
        }

        return view('chat.index', compact('jobs'));
    }

    /**
     * Abre a tela visual do Chat entre o Contratante e o Prestador.
     * Rota correspondente: /chat/{job_id}
     */
    public function show($job_id)
    {
        $job = Job::findOrFail($job_id);
        $usuarioLogado = Auth::user();

        if ($usuarioLogado->id === $job->user_id) {
            // Se eu criei a vaga, procuro o profissional na coluna contractor_id
            $outroUsuario = User::find($job->contractor_id);
            
            // Tratamento para vaga de teste (caso contractor_id esteja NULL no banco)
            if (!$outroUsuario) {
                $outroUsuario = User::where('id', '!=', $usuarioLogado->id)->first() ?? User::find(1);
            }
        } else {
            $outroUsuario = User::find($job->user_id);
        }

        if (!$outroUsuario) {
            $outroUsuario = new User(['id' => 1, 'name' => 'Usuário de Teste']);
        }

            // --- Adicione isso dentro da função show() antes do return ---
    Message::where('job_id', $job_id)
           ->where('receiver_id', $usuarioLogado->id)
           ->where('is_read', false)
           ->update(['is_read' => true]);

    return view('chat.show', compact('job', 'outroUsuario'));

    }

    /**
     * Rota de API (AJAX/Fetch) que o JavaScript chama de 3 em 3 segundos para atualizar a tela
     */
    public function fetchMessages($job_id)
    {
        $messages = Message::where('job_id', $job_id)
                           ->orderBy('created_at', 'asc')
                           ->get();

        return response()->json($messages);
    }

    /**
     * Rota de API (AJAX/Fetch) para gravar as mensagens digitadas no banco de dados
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'job_id' => 'required',
            'message' => 'required|string'
        ]);

        $job = Job::findOrFail($request->job_id);
        $usuarioLogado = Auth::user();

        // Define quem vai receber a mensagem usando a coluna correta
        if ($usuarioLogado->id === $job->user_id) {
            $receiverId = $job->contractor_id;
        } else {
            $receiverId = $job->user_id;
        }

        // Segurança para vagas de teste vazias: evita erro de chave estrangeira (Integrity Constraint Violation)
        if (!$receiverId) {
            $receiverId = User::where('id', '!=', $usuarioLogado->id)->first()->id ?? 1;
        }

        $novaMensagem = Message::create([
            'job_id' => $request->job_id,
            'sender_id' => $usuarioLogado->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'status' => 'Mensagem enviada com sucesso!', 
            'data' => $novaMensagem
        ]);
    }
        /**
     * Retorna a quantidade de mensagens não lidas para o usuário logado.
     */
    public static function getUnreadCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        // Conta quantas mensagens foram enviadas PARA o usuário logado e ainda não foram lidas
        return Message::where('receiver_id', Auth::id())
                      ->where('is_read', false)
                      ->count();
    }

}
