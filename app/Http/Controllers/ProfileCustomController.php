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
        
        // TRAVA DE SEGURANÇA DEFINITIVA: Se o perfil não existir por algum motivo, ele cria na hora
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['bio' => null, 'profile_picture' => null]
        );

        // Regras de validação estritas para a segurança do MVP
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Máximo 2MB por arquivo
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

            // Bloqueia a requisição se a soma das fotos antigas com as novas estourar o limite de 5
            if ($currentImagesCount + count($uploadedImages) > 5) {
                return redirect()->back()->withErrors([
                    'portfolio_images' => 'Limite atingido! Seu portfólio pode ter no máximo 5 fotos no total.'
                ]);
            }

            // Salva cada foto nova na pasta de armazenamento público do Laravel
            foreach ($uploadedImages as $image) {
                $imagePath = $image->store('portfolios', 'public');
                $profile->portfolioImages()->create(['image_path' => $imagePath]);
            }
        }

        // Retorna para a página com uma flag de sucesso na sessão para o feedback visual
        return redirect()->route('profile.custom.edit')->with('status', 'perfil-updated');
    }
        /**
     * Exibe o perfil público de um profissional para os contratantes.
     */
    public function showPublic($id)
    {
        // Busca o usuário prestador trazendo o perfil, as fotos do portfólio e os serviços dele
        $professional = \App\Models\User::with(['profile.portfolioImages', 'servicesOffered'])
            ->findOrFail($id);

        // Garante que o perfil exista na tabela antes de renderizar
        $profile = $professional->profile ?? \App\Models\Profile::create(['user_id' => $professional->id]);

        return view('profile.public-show', compact('professional', 'profile'));
    }
        /**
     * Remove uma imagem específica do portfólio do prestador.
     */
    public function destroyImage($id)
    {
        // Busca a imagem ou retorna 404
        $image = PortfolioImage::with('profile')->findOrFail($id);

        // REGRA DE SEGURANÇA: Garante que a foto pertence ao perfil do usuário logado
        if ($image->profile->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // 1. Apaga o arquivo físico guardado na pasta storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        }

        // 2. Apaga o registro do banco de dados
        $image->delete();

        return redirect()->back()->with('success', 'Foto do portfólio removida com sucesso!');
    }


}
