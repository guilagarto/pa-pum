<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pá-pum</title>
    
    <!-- Bloco de CSS Interno: Super leve e sem cache -->
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Topo da Página */
        .navbar {
            background: #ffffff;
            padding: 15px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            margin: 0;
            color: #4f46e5;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Grid da Dashboard */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Painéis Laterais (Filtro e Criar Vaga) */
        .sidebar-panel {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 24px;
            border: 1px solid #e5e7eb;
            height: fit-content;
            margin-bottom: 20px;
        }

        .sidebar-panel h3 {
            margin-top: 0;
            color: #1f2937;
            font-size: 18px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* Formulários */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #374151;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 14px;
        }

        .form-group textarea {
            resize: vertical;
        }

        /* Botões */
        .btn-link {
            background-color: #4f46e5;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-link:hover {
            background-color: #4338ca;
        }

        .btn-chat {
            background-color: #10b981;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
        }

        .btn-chat:hover {
            background-color: #059669;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        /* Cards do Feed de Vagas */
        .feed-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feed-title {
            margin: 0;
            color: #1f2937;
            font-size: 22px;
        }

        .vaga-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 24px;
            border: 1px solid #e5e7eb;
            position: relative;
        }

        .vaga-card.premium {
            border-left: 5px solid #eab308;
            background: #fefce8;
        }

        .badge-premium {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #eab308;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .vaga-meta {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
            margin-top: 5px;
        }

        .vaga-categoria {
            display: inline-block;
            background: #e0e7ff;
            color: #4338ca;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .alert-sucesso {
            background-color: #def7ec;
            color: #03543f;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- Topo Customizado e Organizado -->
    <nav class="navbar">
        <div class="logo">Pá-pum</div>
        <div class="navbar-actions">
            <a href="{{ route('profile.custom.edit') }}" class="btn-link">Meu Perfil & Portfólio</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Sair</button>
            </form>
        </div>
    </nav>

    <!-- Grid de Conteúdo -->
    <div class="dashboard-grid">
        
        <!-- Lado Esquerdo: Filtros e Criar Vaga -->
        <div>
            <!-- Filtro -->
            <div class="sidebar-panel">
                <h3>Filtrar Oportunidades</h3>
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label for="categoria_filtro">Escolha a Categoria</label>
                        <select id="categoria_filtro" name="categoria" onchange="this.form.submit()">
                            <option value="">Todas as Vagas</option>
                            <option value="Pedreiro" {{ request('categoria') == 'Pedreiro' ? 'selected' : '' }}>Pedreiro</option>
                            <option value="Eletricista" {{ request('categoria') == 'Eletricista' ? 'selected' : '' }}>Eletricista</option>
                            <option value="Manicure" {{ request('categoria') == 'Manicure' ? 'selected' : '' }}>Manicure</option>
                            <option value="Pintor" {{ request('categoria') == 'Pintor' ? 'selected' : '' }}>Pintor</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Publicação de Vaga -->
            <div class="sidebar-panel">
                <h3>Divulgar Nova Vaga</h3>
                <form method="POST" action="{{ route('jobs.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">Título da Vaga</label>
                        <input type="text" id="title" name="title" required placeholder="Ex: Preciso de Pedreiro para Reboco">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria</label>
                        <select id="category" name="category" required>
                            <option value="Pedreiro">Pedreiro</option>
                            <option value="Eletricista">Eletricista</option>
                            <option value="Manicure">Manicure</option>
                            <option value="Pintor">Pintor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição do Serviço</label>
                        <textarea id="description" name="description" rows="4" required placeholder="Descreva os detalhes do trabalho e a localização aproximada..."></textarea>
                    </div>

                    <button type="submit" class="btn-link" style="width: 100%;">Publicar Vaga Grátis</button>
                </form>
            </div>
        </div>

        <!-- Lado Direito: Feed de Oportunidades -->
        <div class="feed-container">
            <hidden class="feed-title"><h2>Vagas Disponíveis</h2></hidden>

            @if(session('success'))
                <div class="alert-sucesso">{{ session('success') }}</div>
            @endif

            @forelse($jobs as $job)
                <div class="vaga-card {{ $job->is_premium ? 'premium' : '' }}">
                    @if($job->is_premium)
                        <span class="badge-premium">DESTAQUE PREMIUM</span>
                    @endif
                    <h3 style="margin: 0; color: #1f2937; font-size: 20px;">{{ $job->title }}</h3>
                    <div class="vaga-meta">Publicado por: <strong>{{ $job->user->name }}</strong> • {{ $job->created_at->diffForHumans() }}</div>
                    <p style="color: #4b5563; font-size: 15px; line-height: 1.5; margin: 10px 0;">{{ $job->description }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                        <span class="vaga-categoria">{{ $job->category }}</span>
                        <a href="#" class="btn-chat">Tenho Interesse (Chat)</a>
                    </div>
                </div>
            @empty
                <div class="sidebar-panel" style="text-align: center; color: #6b7280; padding: 40px;">
                    Nenhuma vaga aberta encontrada para esta categoria neste momento.
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>
