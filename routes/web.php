<?php

use App\Http\Controllers\ProfileCustomController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

// 🌍 Página Inicial Pública (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 🔒 Bloco Geral de Rotas Protegidas (Apenas usuários logados)
Route::middleware(['auth'])->group(function () {

    // 💼 Área da Dashboard e Feed Dinâmico de Prestadores
    Route::get('/dashboard', [JobController::class, 'index'])->name('dashboard');

    // 📂 Painel Privado do Perfil (Biografia, Fotos e Serviços)
    Route::get('/perfil', [ProfileCustomController::class, 'edit'])->name('profile.custom.edit');
    Route::put('/perfil', [ProfileCustomController::class, 'update'])->name('profile.custom.update');
    
    // CORREÇÃO: Nome ajustado estritamente para 'destroy' e método como POST para o servidor aceitar
    Route::post('/perfil/portfolio/deletar/{id}', [ProfileCustomController::class, 'destroyImage'])->name('profile.image.destroy');

    // 🛠️ Gerenciamento de Anúncios de Serviços (Postar, Editar e Excluir)
    Route::post('/vagas', [JobController::class, 'store'])->name('jobs.store');
    Route::put('/vagas/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/vagas/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // 🌍 Visualização do Perfil Público do Profissional (Para os Contratantes)
    Route::get('/prestador/{id}', [ProfileCustomController::class, 'showPublic'])->name('profile.public.show');

});

// Carrega as rotas nativas de autenticação do Breeze (Login, Registro, etc.)
require __DIR__.'/auth.php';
