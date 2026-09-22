<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileCustomController;
use App\Http\Controllers\JobController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Esse bloco garante que apenas usuários logados (auth) consigam acessar o perfil
Route::middleware(['auth'])->group(function () {
    
    // Rota que abre a página com o formulário (GET)
    Route::get('/perfil', [ProfileCustomController::class, 'edit'])->name('profile.custom.edit');
    
    // Rota que recebe os dados do formulário e faz o upload (PUT)
    Route::put('/perfil', [ProfileCustomController::class, 'update'])->name('profile.custom.update');
    Route::get('/dashboard', [JobController::class, 'index'])->name('dashboard');

    // ROTA QUE ESTAVA FALTANDO: Envio do formulário de nova vaga (POST)
    Route::post('/vagas', [JobController::class, 'store'])->name('jobs.store');
    // Rota para visualizar o perfil público de qualquer profissional (GET)
    Route::get('/prestador/{id}', [ProfileCustomController::class, 'showPublic'])->name('profile.public.show');
    // Rota que processa a edição de um serviço existente (PUT)
    Route::put('/vagas/{id}', [JobController::class, 'update'])->name('jobs.update');

    // Rota que processa a exclusão definitiva de um serviço (DELETE)
    Route::delete('/vagas/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
    // Rota que remove uma foto específica do portfólio (DELETE)
    Route::delete('/perfil/portfolio/{id}', [ProfileCustomController::class, 'destroyImage'])->name('profile.image.destroy');


    });

require __DIR__.'/auth.php';
