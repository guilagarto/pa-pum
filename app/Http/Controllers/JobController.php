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
        // Busca as vagas em aberto trazendo junto os dados do usuário criador
        $query = Job::with('user')->where('status', 'open');

        // Aplica o filtro se o usuário selecionar uma categoria
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('category', $request->categoria);
        }

        // Ordena: Premium primeiro, depois as mais recentes
        $jobs = $query->orderBy('is_premium', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('dashboard', compact('jobs'));
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
