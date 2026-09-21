<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileCustomController;
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
    
});
require __DIR__.'/auth.php';
