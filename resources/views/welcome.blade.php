<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pá-pum | Encontre Profissionais Qualificados</title>
    <style>
        /* CSS focado estritamente em Mobile (Ajustado para Celular) */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Header Responsivo */
        header { background: white; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 100%; }
        .logo { font-size: 20px; font-weight: 800; color: #4f46e5; text-decoration: none; }
        .nav-links { display: flex; gap: 10px; align-items: center; }
        .nav-links a { color: #64748b; text-decoration: none; font-size: 13px; font-weight: 600; padding: 4px 6px; border-radius: 4px; }
        .nav-links a.btn-entrar { background-color: #4f46e5; color: white; padding: 6px 12px; }

        /* Seção Hero (Chamada Principal) */
        .hero { background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%); padding: 40px 20px; text-align: center; border-bottom-left-radius: 24px; border-bottom-right-radius: 24px; }
        .hero h1 { font-size: 26px; font-weight: 800; color: #1e1b4b; line-height: 1.2; margin-bottom: 12px; }
        .hero p { font-size: 15px; color: #475569; margin-bottom: 20px; }
        .btn-buscar { display: inline-block; background-color: #4f46e5; color: white; text-decoration: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }

        /* Seção de Contador de Profissionais */
        .stats-section { padding: 30px 20px; text-align: center; }
        .counter-card { background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: inline-block; width: 100%; max-width: 320px; }
        .counter-number { font-size: 36px; font-weight: 800; color: #4f46e5; margin-bottom: 4px; }
        .counter-label { font-size: 14px; color: #64748b; font-weight: 600; }

        /* Seção de Categorias Populares */
        .categories-section { padding: 30px 20px; background-color: #ffffff; }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; text-align: left; color: #0f172a; border-left: 4px solid #4f46e5; padding-left: 8px; }
        .grid-categories { display: flex; flex-direction: column; gap: 12px; }
        .category-card { display: flex; align-items: center; gap: 12px; padding: 14px; background: #f8fafc; border-radius: 12px; text-decoration: none; color: inherit; border: 1px solid #e2e8f0; transition: transform 0.2s; }
        .category-card:active { transform: scale(0.98); background: #f1f5f9; }
        .category-icon { font-size: 22px; background: #e0e7ff; padding: 8px; border-radius: 8px; }
        .category-name { font-weight: 600; font-size: 15px; }

        /* Footer */
        footer { margin-top: auto; background: #0f172a; padding: 20px; text-align: center; color: #94a3b8; font-size: 13px; }
        footer a { color: #cbd5e1; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Header com links organizados para telas pequenas -->
    <header>
        <nav class="nav-container">
            <a href="/" class="logo">Pá-pum</a>
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="{{ route('noticias.index') }}">Notícias</a>

                <a href="#contato">Contato</a>
                @auth
                    <a href="/dashboard" class="btn-entrar">Painel</a>
                @else
                    <a href="/login" class="btn-entrar">Entrar</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Seção Principal de Atração -->
    <section class="hero">
        <h1>Precisa de um profissional qualificado?</h1>
        <p>Encontre Profissionais Qualificados perto de você, converse pelo chat e combine o serviço de forma simples e rápida.</p>
        <a href="/login" class="btn-buscar">Começar Agora</a>
    </section>

    <!-- Contador Baseado no Banco de Dados -->
    <section class="stats-section">
        <div class="counter-card">
            <!-- A variável $totalProfissionais é injetada dinamicamente pelo arquivo de rotas -->
            <div class="counter-number">{{ $totalProfissionais ?? 0 }}</div>
            <div class="counter-label">Profissionais Cadastrados</div>
        </div>
    </section>

    <!-- Categorias mais procuradas -->
    <section class="categories-section" id="categorias">
        <h2 class="section-title">Categorias mais procuradas</h2>
        <div class="grid-categories">
            <a href="/login" class="category-card">
                <span class="category-icon">⚡</span>
                <span class="category-name">Eletricista</span>
            </a>
            <a href="/login" class="category-card">
                <span class="category-icon">🚰</span>
                <span class="category-name">Encanador</span>
            </a>
            <a href="/login" class="category-card">
                <span class="category-icon">🎨</span>
                <span class="category-name">Pintor</span>
            </a>
            <a href="/login" class="category-card">
                <span class="category-icon">🧱</span>
                <span class="category-name">Pedreiro</span>
            </a>
        </div>
    </section>
    <!-- Seção Formulário de Contato -->
    <section class="categories-section" id="contato" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
        <h2 class="section-title">Fale Conosco</h2>
        
        @if(session('sucesso_contato'))
            <div style="background-color: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; margin-bottom: 12px;">
                {{ session('sucesso_contato') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contato.enviar') }}" style="display: flex; flex-direction: column; gap: 12px;">
            @csrf
            <input type="text" name="name" placeholder="Seu Nome completo" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
            <input type="email" name="email" placeholder="Seu melhor E-mail" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
            <textarea name="message" placeholder="Como podemos te ajudar?" rows="4" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; resize: none;"></textarea>
            <button type="submit" style="background-color: #4f46e5; color: white; border: none; padding: 12px; font-weight: bold; border-radius: 8px; font-size: 15px; cursor: pointer;">Enviar Mensagem</button>
        </form>
    </section>

    <!-- Seção Newsletter (Novidades por e-mail) -->
    <section class="categories-section" style="background-color: #4f46e5; color: white; text-align: center; padding: 35px 20px;">
        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 6px;">Fique por dentro das novidades!</h2>
        <p style="font-size: 13px; color: #e0e7ff; margin-bottom: 16px;">Assine nossa newsletter e receba dicas de contratação direto no seu e-mail.</p>
        
        @if(session('sucesso_newsletter'))
            <div style="background-color: #ffffff; color: #15803d; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: bold; margin-bottom: 12px;">
                {{ session('sucesso_newsletter') }}
            </div>
        @endif
        @error('email')
            <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 12px; text-align: left;">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('newsletter.salvar') }}" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf
            <input type="email" name="email" placeholder="Digite seu e-mail" required style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 14px; text-align: center;">
            <button type="submit" style="background-color: #1e1b4b; color: white; border: none; padding: 12px; font-weight: bold; border-radius: 8px; font-size: 14px;">Quero me inscrever</button>
        </form>
    </section>

    <!-- Footer estruturado -->
    <footer>
        <p>&copy; 2026 Pá-pum. Todos os direitos reservados.</p>
        <p style="margin-top: 6px;"><a href="#politica">Política de Privacidade</a></p>
    </footer>

</body>
</html>
