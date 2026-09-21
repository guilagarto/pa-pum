<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\PortfolioImage;

class ProfileCustomController extends Controller
{
    /**
     * Exibe a página do formulário de perfil.
     */
    public function edit()
    {
        // Busca o perfil do usuário logado ou cria um registro em branco vinculado a ele
        $profile = Auth::user()->profile ?? Profile::create(['user_id' => Auth::id()]);

        // Carrega a view externa tradicional que criamos
        return view('profile.custom-edit', compact('profile'));
    }

    /**
     * Processa as atualizações de Biografia, Foto de Perfil e Portfólio.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Regras de validação para segurança do MVP
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'portfolio_images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 1. Atualiza a descrição de trabalho (Biografia)
        $profile->update(['bio' => $request->bio]);

        // 2. Trata o upload da foto principal de perfil
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $profile->update(['profile_picture' => $path]);
        }

        // 3. Trata o portfólio de fotos (Garantindo o teto máximo de 5 imagens no banco)
        if ($request->hasFile('portfolio_images')) {
            $currentImagesCount = $profile->portfolioImages()->count();
            $uploadedImages = $request->file('portfolio_images');

            // Bloqueia se o total ultrapassar 5 fotos
            if ($currentImagesCount + count($uploadedImages) > 5) {
                return redirect()->back()->withErrors([
                    'portfolio_images' => 'Limite atingido! Seu portfólio pode ter no máximo 5 fotos no total.'
                ]);
            }

            // Salva cada foto nova na pasta pública de portfólios
            foreach ($uploadedImages as $image) {
                $imagePath = $image->store('portfolios', 'public');
                $profile->portfolioImages()->create(['image_path' => $imagePath]);
            }
        }

        // Retorna para a página com uma mensagem de sucesso
        return redirect()->route('profile.custom.edit')->with('status', 'perfil-updated');
    }
}
