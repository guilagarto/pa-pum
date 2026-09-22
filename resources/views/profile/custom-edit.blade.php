<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil & Serviços - Pá-pum</title>
    
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

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .card-secao {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            border: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .card-secao h2 {
            color: #1f2937;
            font-size: 20px;
            margin: 0 0 5px 0;
        }

        .card-secao p {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 20px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #374151;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-group input, .form-group textarea {
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

        .avatar-preview img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4f46e5;
            margin-bottom: 10px;
        }

        .grid-portfolio {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 12px;
            margin: 15px 0;
        }

        .grid-portfolio img {
            width: 100%;
            height: 85px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        /* Botões */
        .btn-primario {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-primario:hover {
            background-color: #4338ca;
        }

        .btn-link-voltar {
            color: #4f46e5;
            font-weight: bold;
            text-decoration: none;
            font-size: 15px;
        }

        /* Tabela e Linhas de Ações de Serviços */
        .servico-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .servico-info h4 {
            margin: 0 0 4px 0;
            color: #1f2937;
            font-size: 16px;
        }

        .servico-info span {
            font-size: 12px;
            background: #e0e7ff;
            color: #4338ca;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        .acoes-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-editar {
            background-color: #eab308;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-excluir {
            background-color: #ef4444;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }

        .alert-sucesso {
            background-color: #def7ec;
            color: #03543f;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 15px;
        }

        .alert-erro {
            background-color: #fde8e8;
            color: #9b1c1c;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
        }
                /* Ajuste do Grid do Portfólio com Botão Sobreposto */
        .portfolio-item-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 85px;
        }

        .portfolio-item-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .btn-deletar-foto {
            position: absolute;
            top: 4px;
            right: 4px;
            background-color: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 4px;
            width: 22px;
            height: 22px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: background 0.2s;
        }

        .btn-deletar-foto:hover {
            background-color: rgba(220, 38, 38, 1);
        }

    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="logo">Pá-pum</a>
        <a href="{{ route('dashboard') }}" class="btn-link-voltar">← Voltar para a Dashboard</a>
    </nav>

    <div class="container">
        
        @if(session('success'))
            <div class="alert-sucesso" style="margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <!-- SEÇÃO 1: Dados Profissionais (Bio e Portfólio) -->
        <div class="card-secao">
            <h2>Dados do meu Perfil</h2>
            <p>Atualize sua biografia profissional e gerencie as fotos do seu portfólio de trabalho.</p>

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
                    <label for="bio">Sua Biografia / Apresentação</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Descreva sua experiência geral para os contratantes...">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                                <div class="form-group">
                    <label>Fotos do Portfólio (Máximo 5 fotos no total)</label>
                    @if($profile->portfolioImages->count() > 0)
                        <div class="grid-portfolio">
                            @foreach($profile->portfolioImages as $image)
                                <div class="portfolio-item-wrapper">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Trabalho do portfólio">
                                    
                                    <!-- Formulário de Exclusão da Imagem Individual -->
                                                                       <!-- Formulário Corrigido de Exclusão da Imagem Individual -->
                                    <form method="POST" action="{{ route('profile.image.destroy', $image->id) }}" onsubmit="return confirm('Deseja remover esta foto do seu portfólio?')" style="margin:0;">
                                        @csrf
                                        <!-- Removemos a linha do @method('DELETE') daqui -->
                                        <button type="submit" class="btn-deletar-foto" title="Excluir foto">X</button>
                                    </form>

                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input id="portfolio_images" name="portfolio_images[]" type="file" multiple accept="image/*">
                    <p style="font-size: 12px; color: #6b7280; mt-1;">Você possui {{ $profile->portfolioImages->count() }} de 5 fotos salvas.</p>
                </div>


                <button type="submit" class="btn-primario">Salvar Perfil</button>
                @if (session('status') === 'perfil-updated')
                    <span style="color: #059669; font-size: 14px; margin-left: 10px; font-weight: bold;">Alterações salvas!</span>
                @endif
            </form>
        </div>
        <!-- SEÇÃO 2: Meus Anúncios de Serviços -->
        <div class="card-secao">
            <h2>Meus Serviços Anunciados</h2>
            <p>Anuncie novos trabalhos ou gerencie, edite e exclua os anúncios ativos que aparecem no feed.</p>

            <!-- Lista de Serviços Atuais -->
            <div style="margin-bottom: 25px;">
                <h3 style="font-size: 16px; color: #374151; margin-bottom: 10px;">Anúncios Ativos</h3>
                @forelse(Auth::user()->servicesOffered()->where('status', 'open')->get() as $servico)
                    <div class="servico-item">
                        <div class="servico-info">
                            <h4>{{ $servico->title }}</h4>
                            <span>{{ $servico->category }}</span>
                        </div>
                        <div class="acoes-buttons">
                            <!-- Botão de Editar que preenche o formulário via JavaScript abaixo -->
                            <button class="btn-editar" onclick="carregarEdicao({{ $servico->id }}, '{{ $servico->title }}', '{{ $servico->category }}', '{{ e($servico->description) }}')">Editar</button>
                            
                            <!-- Formulário de Exclusão Direta -->
                            <form method="POST" action="{{ route('jobs.destroy', $servico->id) }}" onsubmit="return confirm('Deseja mesmo remover este anúncio de serviço?')" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-excluir">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="color: #6b7280; font-size: 14px; font-style: italic;">Você ainda não cadastrou nenhum anúncio de serviço.</p>
                @endforelse
            </div>

            <!-- Formulário Unificado: Criação e Edição Dinâmica -->
            <h3 id="form-titulo" style="font-size: 16px; color: #374151; margin-bottom: 15px; border-top: 1px solid #f3f4f6; padding-top: 15px;">Cadastrar Novo Serviço</h3>
            
            <form id="form-servico" method="POST" action="{{ route('jobs.store') }}">
                @csrf
                <!-- Campo oculto para mudar o método para PUT dinamicamente quando for editar -->
                <div id="metodo-extra"></div>

                <div class="form-group">
                    <label for="title">Título do Anúncio de Serviço</label>
                    <input type="text" id="title_servico" name="title" required placeholder="Ex: Ofereço Instalação Elétrica Residencial Completa">
                </div>

                <div class="form-group">
                    <label for="category">Profissão / Categoria</label>
                    <input type="text" id="category_servico" name="category" required placeholder="Ex: Eletricista, Marceneiro, Pedreiro">
                </div>

                <div class="form-group">
                    <label for="description">Descrição detalhada do Serviço</label>
                    <textarea id="description_servico" name="description" rows="4" required placeholder="Descreva os serviços que realiza, ferramentas que possui e sua região de atendimento..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="submit" id="btn-submit-servico" class="btn-primario" style="background-color: #10b981;">Publicar Serviço</button>
                    <button type="button" id="btn-cancelar" onclick="cancelarEdicao()" style="display: none; background-color: #6b7280; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">Cancelar Edição</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Script Inteligente: Alterna o formulário entre Criação e Edição instantaneamente -->
    <script>
        function carregarEdicao(id, titulo, categoria, descricao) {
            // 1. Altera os textos informativos
            document.getElementById('form-titulo').innerText = "Editar Meu Serviço";
            document.getElementById('btn-submit-servico').innerText = "Salvar Alterações do Serviço";
            document.getElementById('btn-submit-servico').style.backgroundColor = "#eab308";
            document.getElementById('btn-cancelar').style.display = "inline-block";

            // 2. Muda a rota de destino do formulário para a rota de update
            document.getElementById('form-servico').action = "/vagas/" + id;

            // 3. Injeta a tag Blade @method('PUT') oculta de forma nativa
            document.getElementById('metodo-extra').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // 4. Preenche os inputs com os dados originais do anúncio
            document.getElementById('title_servico').value = titulo;
            document.getElementById('category_servico').value = categoria;
            document.getElementById('description_servico').value = descricao;

            // Rola a tela até o formulário de forma suave
            document.getElementById('form-titulo').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicao() {
            // Reseta o formulário para o estado padrão de criação grátis
            document.getElementById('form-titulo').innerText = "Cadastrar Novo Serviço";
            document.getElementById('btn-submit-servico').innerText = "Publicar Serviço";
            document.getElementById('btn-submit-servico').style.backgroundColor = "#10b981";
            document.getElementById('btn-cancelar').style.display = "none";
            document.getElementById('form-servico').action = "{{ route('jobs.store') }}";
            document.getElementById('metodo-extra').innerHTML = '';
            document.getElementById('form-servico').reset();
        }
    </script>

</body>
</html>
