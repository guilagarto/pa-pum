<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil & Serviços - Pá-pum</title>
    
    <style>
        body { background-color: #f3f4f6; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .navbar { background: #ffffff; padding: 15px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .navbar .logo { color: #4f46e5; font-size: 24px; font-weight: bold; text-decoration: none; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 15px; }
        .card-secao { background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 25px; border: 1px solid #e5e7eb; margin-bottom: 25px; }
        .card-secao h2 { color: #1f2937; font-size: 20px; margin: 0 0 5px 0; }
        .card-secao p { color: #6b7280; font-size: 14px; margin: 0 0 20px 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; color: #374151; font-size: 14px; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #d1d5db; box-sizing: border-box; font-size: 14px; font-family: inherit; }
        .form-group textarea { resize: vertical; }
        .avatar-preview img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #4f46e5; margin-bottom: 10px; }
        .grid-portfolio { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; margin: 15px 0; }
        .portfolio-item-wrapper { position: relative; display: inline-block; width: 100%; height: 85px; }
        .portfolio-item-wrapper img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; }
        .btn-deletar-foto { position: absolute; top: 4px; right: 4px; background-color: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 4px; width: 22px; height: 22px; font-size: 11px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .btn-primario { background-color: #4f46e5; color: #ffffff; padding: 12px 20px; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; display: inline-block; text-decoration: none; text-align: center; }
        .btn-primario:hover { background-color: #4338ca; }
        .btn-link-voltar { color: #4f46e5; font-weight: bold; text-decoration: none; font-size: 15px; }
        .servico-item { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .servico-info h4 { margin: 0 0 4px 0; color: #1f2937; font-size: 16px; }
        .servico-info span { font-size: 12px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .acoes-buttons { display: flex; gap: 8px; }
        .btn-editar { background-color: #eab308; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold; }
        .btn-excluir { background-color: #ef4444; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold; }
        .alert-sucesso { background-color: #def7ec; color: #03543f; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 20px; }
        .alert-erro { background-color: #fde8e8; color: #9b1c1c; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 20px; }
        .historico-badge { font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 20px; }
        .badge-andamento { background-color: #fef3c7; color: #d97706; }
        .badge-concluido { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="logo">Pá-pum</a>
        <a href="{{ route('dashboard') }}" class="btn-link-voltar">← Voltar para a Dashboard</a>
    </nav>

    <div class="container">
        
        @if(session('success'))
            <div class="alert-sucesso">{{ session('success') }}</div>
        @endif

        <!-- SEÇÃO 1: Dados do Perfil -->
        <div class="card-secao">
            <h2>Dados do meu Perfil</h2>
            <p>Atualize sua biografia profissional e gerencie as fotos do seu portfólio de trabalho.</p>

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
                    <textarea id="bio" name="bio" rows="4">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Fotos do Portfólio (Máximo 5 fotos)</label>
                    @if($profile->portfolioImages->count() > 0)
                        <div class="grid-portfolio">
                            @foreach($profile->portfolioImages as $image)
                                <div class="portfolio-item-wrapper">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Portfólio">
                                    <button type="submit" form="delete-photo-{{ $image->id }}" class="btn-deletar-foto">X</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input id="portfolio_images" name="portfolio_images[]" type="file" multiple accept="image/*">
                </div>

                <button type="submit" class="btn-primario">Salvar Perfil</button>
            </form>
        </div>

        <!-- SEÇÃO 2: Meus Anúncios de Serviços -->
        <div class="card-secao">
            <h2>Meus Serviços Anunciados</h2>
            <p>Anuncie novos trabalhos ou gerencie os anúncios ativos que aparecem no feed.</p>

            <div style="margin-bottom: 25px;">
                @forelse(Auth::user()->servicesOffered()->where('status', 'open')->get() as $servico)
                    <div class="servico-item">
                        <div class="servico-info">
                            <h4>{{ $servico->title }}</h4>
                            <span>{{ $servico->category }}</span>
                        </div>
                        <div class="acoes-buttons">
                            <button class="btn-editar" onclick="carregarEdicao({{ $servico->id }}, '{{ $servico->title }}', '{{ $servico->category }}', '{{ e($servico->description) }}')">Editar</button>
                            <form method="POST" action="{{ route('jobs.destroy', $servico->id) }}" onsubmit="return confirm('Deseja remover?')" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-excluir">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="color: #6b7280; font-size: 14px; font-style: italic;">Nenhum anúncio de serviço cadastrado.</p>
                @endforelse
            </div>

            <h3 id="form-titulo" style="font-size: 16px; color: #374151; margin-bottom: 15px;">Cadastrar Novo Serviço</h3>
            <form id="form-servico" method="POST" action="{{ route('jobs.store') }}">
                @csrf <div id="metodo-extra"></div>
                <div class="form-group"><label>Título</label><input type="text" id="title_servico" name="title" required></div>
                <div class="form-group"><label>Categoria</label><input type="text" id="category_servico" name="category" required></div>
                <div class="form-group"><label>Descrição</label><textarea id="description_servico" name="description" rows="4" required></textarea></div>
                <button type="submit" id="btn-submit-servico" class="btn-primario" style="background-color: #10b981;">Publicar Serviço</button>
                <button type="button" id="btn-cancelar" onclick="cancelarEdicao()" style="display: none; background-color: #6b7280; color: white; padding: 12px 20px; border: none; border-radius: 6px;">Cancelar</button>
            </form>
        </div>
        <!-- SEÇÃO 3: HISTÓRICO DUPLO -->
        
        <!-- Bloco A: Trabalhos Realizados (Como Prestador) -->
        <div class="card-secao">
            <h2>Histórico de Trabalhos Realizados (Prestador)</h2>
            <p>Abaixo estão listados os contatos abertos por clientes e os serviços prestados por você.</p>
            
            @forelse(Auth::user()->servicesOffered()->whereIn('status', ['in_progress', 'completed'])->get() as $atendimento)
                <div class="servico-item" style="background: #ffffff; border-left: 4px solid #4f46e5;">
                    <div class="servico-info">
                        <h4>{{ $atendimento->title }}</h4>
                        <p style="margin: 4px 0; font-size: 13px; color: #4b5563;">Contratante: <strong>{{ $atendimento->contractor_id ? \App\Models\User::find($atendimento->contractor_id)->name : 'Interessado' }}</strong></p>
                    </div>
                    <div>
                        @if($atendimento->status == 'in_progress')
                            <span class="historico-badge badge-andamento">Aguardando Avaliação</span>
                        @else
                            <span class="historico-badge badge-concluido">Concluído</span>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color: #6b7280; font-size: 14px; font-style: italic;">Nenhum cliente iniciou um atendimento com você ainda.</p>
            @endforelse
        </div>

        <!-- Bloco B: Serviços Contratados (Como Cliente + Sistema de Avaliação por Estrelas) -->
        <div class="card-secao">
            <h2>Histórico de Serviços Contratados (Cliente)</h2>
            <p>Gerencie os profissionais que você contratou e atribua notas em estrelas para os serviços concluídos.</p>
            
            @forelse(Auth::user()->servicesContracted as $contratacao)
                <div class="servico-item" style="background: #ffffff; border-left: 4px solid #10b981;">
                    <div class="servico-info">
                        <h4>{{ $contratacao->title }}</h4>
                        <p style="margin: 4px 0; font-size: 13px; color: #4b5563;">Prestador: <strong>{{ $contratacao->user->name }}</strong></p>
                    </div>
                    
                    <div>
                        @if($contratacao->status == 'in_progress')
                            <!-- Formulário de Avaliação Dinâmica de 1 a 5 estrelas -->
                            <form method="POST" action="{{ route('profile.review.store', $contratacao->id) }}" style="display: flex; gap: 8px; align-items: center; margin: 0;">
                                @csrf
                                <select name="rating" required style="padding: 6px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 13px; font-weight: bold; color: #eab308;">
                                    <option value="5">★★★★★ (Excelente)</option>
                                    <option value="4">★★★★☆ (Ótimo)</option>
                                    <option value="3">★★★☆☆ (Bom)</option>
                                    <option value="2">★★☆☆☆ (Regular)</option>
                                    <option value="1">★☆☆☆☆ (Ruim)</option>
                                </select>
                                <button type="submit" class="btn-primario" style="background-color: #10b981; padding: 6px 12px; font-size: 13px;">Avaliar e Fechar</button>
                            </form>
                        @else
                            <span class="historico-badge badge-concluido">Serviço Avaliado</span>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color: #6b7280; font-size: 14px; font-style: italic;">Você ainda não abriu chat ou contratou nenhum prestador.</p>
            @endforelse
        </div>

    </div>

    <!-- Formulários Ocultos de Deleção das Fotos -->
    @if($profile->portfolioImages->count() > 0)
        @foreach($profile->portfolioImages as $image)
            <form id="delete-photo-{{ $image->id }}" method="POST" action="{{ route('profile.image.destroy', $image->id) }}" onsubmit="return confirm('Deseja remover esta foto do seu portfólio?')" style="display: none;">
                @csrf
            </form>
        @endforeach
    @endif

    <script>
        function carregarEdicao(id, titulo, categoria, descricao) {
            document.getElementById('form-titulo').innerText = "Editar Meu Serviço";
            document.getElementById('btn-submit-servico').innerText = "Salvar Alterações do Serviço";
            document.getElementById('btn-submit-servico').style.backgroundColor = "#eab308";
            document.getElementById('btn-cancelar').style.display = "inline-block";
            document.getElementById('form-servico').action = "/vagas/" + id;
            document.getElementById('metodo-extra').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('title_servico').value = titulo;
            document.getElementById('category_servico').value = categoria;
            document.getElementById('description_servico').value = descricao;
            document.getElementById('form-titulo').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicao() {
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
