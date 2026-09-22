<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de {{ $professional->name }} - Pá-pum</title>
    
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #ffffff;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            color: #4f46e5;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .card-publico {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .profile-header img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4f46e5;
        }

        .profile-info h2 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
        }

        .estrelas {
            color: #eab308;
            font-size: 18px;
            margin-top: 5px;
        }

        .section-title {
            color: #1f2937;
            font-size: 18px;
            margin: 25px 0 12px 0;
            font-weight: bold;
        }

        .bio-text {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #4f46e5;
        }

        /* Grid de Portfólio Limite de 5 Fotos */
        .grid-portfolio {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .grid-portfolio img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s;
        }

        .grid-portfolio img:hover {
            transform: scale(1.03);
        }

        .btn-acao-chat {
            display: block;
            width: 100%;
            background-color: #10b981;
            color: white;
            padding: 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            box-sizing: border-box;
            transition: background 0.2s;
        }

        .btn-acao-chat:hover {
            background-color: #059669;
        }

        @media (max-width: 600px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="logo">Pá-pum</a>
        <a href="{{ route('dashboard') }}" style="color: #4f46e5; font-weight: bold; text-decoration: none; font-size: 15px;">← Voltar para Vagas</a>
    </nav>

    <div class="profile-container">
        <div class="card-publico">
            
            <div class="profile-header">
                @if($profile->profile_picture)
                    <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Foto de {{ $professional->name }}">
                @else
                    <div style="width: 90px; height: 90px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6b7280; font-size: 24px; border: 3px solid #4f46e5;">
                        {{ strtoupper(substr($professional->name, 0, 1)) }}
                    </div>
                @endif
                
                <div class="profile-info">
                    <h2>{{ $professional->name }}</h2>
                    <div class="estrelas">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $profile->rating_cache ? '★' : '☆' }}
                        @endfor
                        <span style="font-size: 14px; color: #6b7280;">({{ number_format($profile->rating_cache, 1) }})</span>
                    </div>
                </div>
            </div>

            <div class="section-title">Sobre o Profissional</div>
            <div class="bio-text">
                {{ $profile->bio ?? 'Este profissional ainda não preencheu a descrição do seu perfil.' }}
            </div>

                       <div class="section-title">Portfólio de Trabalhos</div>
            @if($profile->portfolioImages->count() > 0)
                <div class="grid-portfolio">
                    @foreach($profile->portfolioImages as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Trabalho realizado">
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; font-size: 14px; font-style: italic; margin-bottom: 25px;">Nenhuma foto de trabalho adicionada ao portfólio ainda.</p>
            @endif

            <!-- FORMULÁRIO CORRIGIDO: Cria o vínculo de histórico e abre o chat -->
            <form method="POST" action="{{ route('jobs.contract', $professional->servicesOffered->first()->id ?? 0) }}">
                @csrf
                @if($professional->servicesOffered->where('status', 'open')->count() > 0)
                    <button type="submit" class="btn-acao-chat" style="border: none; width: 100%; font-family: inherit; font-size: 16px; font-weight: bold; cursor: pointer;">
                        Iniciar Conversa no Chat (Contratar)
                    </button>
                @else
                    <button type="button" class="btn-acao-chat" style="background-color: #9ca3af; cursor: not-allowed; border: none; width: 100%;" disabled>
                        Nenhum serviço ativo para contratar
                    </button>
                @endif
            </form>

        </div>
    </div>

</body>
</html>
