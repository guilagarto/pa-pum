<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;

class PublicPageController extends Controller
{
    // Listagem de todas as notícias/dicas
    public function noticias()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('noticias.index', compact('posts'));
    }

    // Visualização de uma notícia específica
    public function lerNoticia($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('noticias.show', compact('post'));
    }

    // Processamento do Formulário de Contato
    public function enviarContato(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);

        $dados = $request->only('name', 'email', 'message');

        // Disparo de E-mail para o Administrador
        Mail::send([], [], function ($message) use ($dados) {
            $message->to('admin@papum.com.br') // Substitua pelo e-mail do admin da Hostinger
                    ->subject('Novo Contato do Site - Pá-pum')
                    ->html("<h3>Nova mensagem recebida:</h3>
                            <p><strong>Nome:</strong> {$dados['name']}</p>
                            <p><strong>E-mail:</strong> {$dados['email']}</p>
                            <p><strong>Mensagem:</strong> {$dados['message']}</p>");
        });

        // Disparo de E-mail de confirmação para o Usuário
        Mail::send([], [], function ($message) use ($dados) {
            $message->to($dados['email'])
                    ->subject('Recebemos sua mensagem! - Pá-pum')
                    ->html("<h3>Olá, {$dados['name']}!</h3>
                            <p>Obrigado por entrar em contato conosco. Sua mensagem já foi encaminhada para nossa equipe e responderemos o mais breve possível.</p>");
        });

        return back()->with('sucesso_contato', 'Mensagem enviada com sucesso! Verifique seu e-mail.');
    }

    // Inscrição na Newsletter
    public function salvarNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:leads,email'
        ], [
            'email.unique' => 'Este e-mail já está cadastrado em nossa newsletter!'
        ]);

        Lead::create(['email' => $request->email]);

        return back()->with('sucesso_newsletter', 'Inscrição realizada com sucesso! 🎉');
    }
}
