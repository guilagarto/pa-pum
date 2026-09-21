<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil Profissional - Pá-pum</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="dashboard-container">
        <div class="card-perfil">
            <h2>Configurações do Portfólio</h2>
            <p>Atualize sua biografia de prestador de serviços e gerencie suas fotos de trabalho.</p>

            @if ($errors->any())
                <div class="alert-erro">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.custom.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="profile_picture">Foto de Perfil</label>
                    @if($profile->profile_picture)
                        <div class="avatar-preview">
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Foto de perfil">
                        </div>
                    @endif
                    <input id="profile_picture" name="profile_picture" type="file" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="bio">Descreva seu Trabalho (Bio)</label>
                    <textarea id="bio" name="bio" rows="5" placeholder="Ex: Sou eletricista residencial com 5 anos de experiência..."></textarea>
                </div>

                <div class="form-group">
                    <label for="portfolio_images">Fotos do Portfólio (Máximo 5 fotos)</label>
                    
                    @if($profile->portfolioImages->count() > 0)
                        <div class="grid-portfolio">
                            @foreach($profile->portfolioImages as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto do portfólio">
                            @endforeach
                        </div>
                    @endif

                    <input id="portfolio_images" name="portfolio_images[]" type="file" multiple accept="image/*">
                </div>

                <button type="submit" class="btn-salvar">Salvar Alterações</button>

                @if (session('status') === 'perfil-updated')
                    <div class="alert-sucesso">Alterações salvas com sucesso!</div>
                @endif
            </form>
        </div>
    </div>

</body>
</html>
