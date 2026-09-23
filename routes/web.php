<?php

use App\Http\Controllers\ProfileCustomController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ChatController; // <-- Garanta essa importação no topo!
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use App\Models\Profile; // Certifique-se de importar o modelo do perfil do profissional no topo
use App\Http\Controllers\PublicPageController;



// 🌍 Página Inicial Pública (Landing Page)
Route::get('/', function () {
    // 1. Conta o total de profissionais (se baseia nos registros da tabela profiles ou usuários)
    // Se você tiver uma coluna 'role' ou tabela 'profiles', ajuste aqui. Se não, contamos todos os usuários para o teste:
    $totalProfissionais = DB::table('profiles')->count() ?: User::count();

    // 2. Busca as categorias mais procuradas com base na quantidade de vagas abertas na tabela 'jobs'
    $categoriasPopulares = Job::select('category', DB::raw('count(*) as total'))
        ->groupBy('category')
        ->orderBy('total', 'desc')
        ->take(4) // Pega as 4 categorias mais famosas
        ->get();

    // Se o banco estiver vazio em testes, criamos dados fictícios para a tela não ficar em branco
    if ($categoriasPopulares->isEmpty()) {
        $categoriasPopulares = collect([
            (object)['category' => 'Eletricista', 'total' => 12],
            (object)['category' => 'Encanador', 'total' => 8],
            (object)['category' => 'Pintor', 'total' => 5],
            (object)['category' => 'Diarista', 'total' => 3],
        ]);
    }

    return view('welcome', compact('totalProfissionais', 'categoriasPopulares'));
});

// 🔒 Bloco Geral de Rotas Protegidas (Apenas usuários logados)
Route::middleware(['auth'])->group(function () {

    // 💼 Área da Dashboard e Feed Dinâmico de Prestadores
    Route::get('/dashboard', [JobController::class, 'index'])->name('dashboard');

    // 📂 Painel Privado do Perfil (Biografia, Fotos e Serviços)
    Route::get('/perfil', [ProfileCustomController::class, 'edit'])->name('profile.custom.edit');
    Route::put('/perfil', [ProfileCustomController::class, 'update'])->name('profile.custom.update');
    Route::post('/perfil/portfolio/deletar/{id}', [ProfileCustomController::class, 'destroyImage'])->name('profile.image.destroy');

    // 🛠️ Gerenciamento de Anúncios de Serviços (Postar, Editar e Excluir)
    Route::post('/vagas', [JobController::class, 'store'])->name('jobs.store');
    Route::put('/vagas/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/vagas/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // 🌍 Visualização do Perfil Público do Profissional (Para os Contratantes)
    Route::get('/prestador/{id}', [ProfileCustomController::class, 'showPublic'])->name('profile.public.show');

    // 📋 ROTAS DO CHAT E HISTÓRICO (O que estava faltando!):
    Route::get('/conversas', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/vagas/contratar/{id}', [JobController::class, 'startContract'])->name('jobs.contract');
    Route::get('/chat/{job_id}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{job_id}/mensagens', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/{job_id}/enviar', [ChatController::class, 'sendMessage'])->name('chat.send');

    // ⭐ Avaliação por Estrelas
    Route::post('/perfil/avaliar/{id}', [JobController::class, 'storeReview'])->name('profile.review.store');
});
    Route::get('/', function () {
        // Conta quantos profissionais ativos existem na base de dados
        $totalProfissionais = Profile::count(); 

        return view('welcome', compact('totalProfissionais'));
    });
    // Rotas do Blog/Notícias
Route::get('/noticias', [PublicPageController::class, 'noticias'])->name('noticias.index');
Route::get('/noticias/{slug}', [PublicPageController::class, 'lerNoticia'])->name('noticias.show');

// Rotas de Captação e Formulários
Route::post('/contato/enviar', [PublicPageController::class, 'enviarContato'])->name('contato.enviar');
Route::post('/newsletter/salvar', [PublicPageController::class, 'salvarNewsletter'])->name('newsletter.salvar');

require __DIR__.'/auth.php';
