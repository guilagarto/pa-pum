<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Exibe o Feed de Prestadores na Dashboard.
     */
    public function index(Request $request)
    {
        // Coleta todas as categorias únicas ativas no banco para alimentar o filtro dinâmico
        $categoriasDisponiveis = Job::where('status', 'open')
                                    ->pluck('category')
                                    ->unique()
                                    ->filter();

        // Inicia a busca trazenda os dados vinculados do perfil de cada usuário
        $query = Job::with('user.profile')->where('status', 'open');

        // Aplica o filtro de categorias caso o contratante selecione alguma
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('category', $request->categoria);
        }

        // Ordenação estratégica do MVP: Premium primeiro, depois anúncios mais novos
        $jobs = $query->orderBy('is_premium', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('dashboard', compact('jobs', 'categoriasDisponiveis'));
    }

    /**
     * Salva um novo anúncio de serviço no banco de dados.
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

        return redirect()->route('profile.custom.edit')->with('success', 'Anúncio de serviço publicado com sucesso!');
    }

    /**
     * Salva as alterações de um anúncio de serviço existente.
     */
    public function update(Request $request, $id)
    {
        // Busca a vaga ou retorna erro 404 caso não encontre
        $job = Job::findOrFail($id);

        // REGRA DE SEGURANÇA: Bloqueia caso o usuário tente editar o serviço de outra pessoa
        if ($job->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|max:100',
        ]);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return redirect()->route('profile.custom.edit')->with('success', 'Anúncio de serviço atualizado com sucesso!');
    }

    /**
     * Remove um anúncio de serviço de forma definitiva.
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);

        // REGRA DE SEGURANÇA: Bloqueia caso o usuário tente apagar o serviço de outra pessoa
        if ($job->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Deleta o registro do banco de dados
        $job->delete();

        return redirect()->route('profile.custom.edit')->with('success', 'Anúncio de serviço removido com sucesso!');
    }
}
