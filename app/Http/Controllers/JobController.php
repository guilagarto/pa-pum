<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Exibe o Feed de Vagas na Dashboard
     */
   public function index(Request $request)
{
    // 1. FILTRO DINÂMICO: Busca todas as categorias únicas já cadastradas no banco de dados
    $categoriasDisponiveis = \App\Models\Job::where('status', 'open')
                                           ->pluck('category')
                                           ->unique()
                                           ->filter();

    // 2. Inicia a busca de serviços disponíveis
    $query = Job::with('user.profile')->where('status', 'open');

    // Aplica o filtro se o contratante escolher alguma categoria
    if ($request->has('categoria') && $request->categoria != '') {
        $query->where('category', $request->categoria);
    }

    // Premium primeiro, depois os anúncios mais recentes
    $jobs = $query->orderBy('is_premium', 'desc')
                  ->orderBy('created_at', 'desc')
                  ->get();

    return view('dashboard', compact('jobs', 'categoriasDisponiveis'));
}


    /**
     * Salva uma nova vaga no banco de dados
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|max:100',
        ]);

        Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'is_premium' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Vaga divulgada com sucesso!');
    }
}
