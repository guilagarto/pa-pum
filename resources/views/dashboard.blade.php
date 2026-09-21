<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pá-pum</title>
    
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Topo Responsivo */
        .navbar {
            background: #ffffff;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .navbar .logo {
            color: #4f46e5;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Grid Adaptável: Computador (2 colunas), Celular (1 coluna) */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .sidebar-panel {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            border: 1px solid #e5e7eb;
            height: fit-content;
        }

        .sidebar-panel h3 {
            margin-top: 0;
            color: #1f2937;
            font-size: 18px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

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
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            box-sizing: border-box;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
        }

        /* Botões */
        .btn-link {
            background-color: #4f46e5;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            box-sizing: border-box;
        }

        .btn-link:hover {
            background-color: #4338ca;
        }

        .btn-chat {
            background-color: #10b981;
            color: white;
            padding: 10px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
            text-align: center;
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

        /* Feed de Serviços */
        .feed-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-width: 0;
        }

        .vaga-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            border: 1px solid #e5e7eb;
            position: relative;
            word-wrap: break-word;
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

        /* Estrelas de Avaliação */
        .estrelas-container {
            color: #eab308;
            font-size: 16px;
            margin: 5px 0;
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

        /* 📱 RESPONSIVIDADE PARA CELULAR */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .navbar-actions {
                width: 100%;
                justify-content: center;
            }
            
            .vaga-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Topo Customizado e Responsivo -->
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
        
        <!-- Lado Esquerdo: Filtros Dinâmicos e Cadastro de Serviço -->
        <div>
            <!-- Filtro Dinâmico -->
            <div class="sidebar-panel" style="margin-bottom: 20px;">
                <h3>Filtrar Prestadores</h3>
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label for="categoria_filtro">Escolha a Categoria</label>
                        <select id="categoria_filtro" name="categoria" onchange="this.form.submit()">
                            <option value="">Todas as Categorias</option>
                            @foreach($categoriasDisponiveis as $cat)
                                <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Publicação de Serviço Oferecido -->
            <div class="sidebar-panel">
                <h3>Divulgar Meu Serviço</h3>
                <form method="POST" action="{{ route('jobs.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">Título do seu Anúncio</label>
                        <input type="text" id="title" name="title" required placeholder="Ex: Ofereço Serviço de Eletricista Geral">
                    </div>

                    <div class="form-group">
                        <label for="category">Sua Profissão / Categoria</label>
                        <input type="text" id="category" name="category" required placeholder="Ex: Pedreiro, Manicure, Marceneiro">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição do que você faz</label>
                        <textarea id="description" name="description" rows="4" required placeholder="Descreva suas especialidades, região de atendimento e detalhes do serviço..."></textarea>
                    </div>

                    <button type="submit" class="btn-link" style="width: 100%;">Publicar Anúncio Grátis</button>
                </form>
            </div>
        </div>

        <!-- Lado Direito: Feed Dinâmico de Serviços -->
        <div class="feed-container">
            <h2 style="margin: 0; color: #1f2937; font-size: 22px;">Profissionais Disponíveis</h2>

            @if(session('success'))
                <div class="alert-sucesso">{{ session('success') }}</div>
            @endif

            @forelse($jobs as $job)
                <div class="vaga-card {{ $job->is_premium ? 'premium' : '' }}">
                    @if($job->is_premium)
                        <span class="badge-premium">DESTAQUE PREMIUM</span>
                    @endif
                    
                    <h3 style="margin: 0; color: #1f2937; font-size: 20px;">{{ $job->title }}</h3>
                    
                    <!-- Sistema de Estrelas Dinâmico -->
                    <div class="estrelas-container">
                        @php $rating = optional($job->user->profile)->rating_cache ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $rating ? '★' : '☆' }}
                        @endfor
                        <span style="font-size: 13px; color: #6b7280;">({{ number_format($rating, 1) }})</span>
                    </div>

                    <div class="vaga-meta">Anunciado por: <strong>{{ $job->user->name }}</strong> • {{ $job->created_at->diffForHumans() }}</div>
                    <p style="color: #4b5563; font-size: 15px; line-height: 1.5; margin: 10px 0;">{{ $job->description }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                        <span class="vaga-categoria">{{ $job->category }}</span>
                        <a href="#" class="btn-chat">Contratar (Chat)</a>
                    </div>
                </div>
            @empty
                <div class="sidebar-panel" style="text-align: center; color: #6b7280; padding: 40px;">
                    Nenhum profissional oferecendo serviços nesta categoria no momento.
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>
