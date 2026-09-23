<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pá-pum | Criar Conta</title>
    <style>
        /* CSS Otimizado para Mobile */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .register-container { background: white; width: 100%; max-width: 360px; padding: 24px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        
        .logo-box { text-align: center; margin-bottom: 20px; }
        .logo-text { font-size: 28px; font-weight: 800; color: #4f46e5; text-decoration: none; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }

        .form-group { margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 13px; font-weight: 600; color: #475569; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background-color: #f8fafc; transition: border-color 0.2s; }
        input:focus { outline: none; border-color: #4f46e5; background-color: white; }

        .btn-registrar { width: 100%; background-color: #4f46e5; color: white; border: none; padding: 12px; font-size: 15px; font-weight: bold; border-radius: 8px; cursor: pointer; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .btn-registrar:active { transform: scale(0.98); }

        .footer-links { text-align: center; margin-top: 16px; font-size: 13px; }
        .footer-links a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        
        /* Alertas de validação de erro em vermelho */
        .error-message { color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 500; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="logo-box">
        <a href="/" class="logo-text">Pá-pum</a>
        <p class="subtitle">Crie sua conta em poucos segundos</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Campo Nome -->
        <div class="form-group">
            <label id="label-name" for="name">Nome Completo</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Campo Email -->
        <div class="form-group">
            <label id="label-email" for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Campo Senha -->
        <div class="form-group">
            <label id="label-password" for="password">Senha</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
            @error('password') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Confirmar Senha -->
        <div class="form-group">
            <label id="label-password_confirmation" for="password_confirmation">Confirmar Senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-registrar">Cadastrar</button>

        <div class="footer-links">
            <a href="{{ route('login') }}">Já tem uma conta? Entrar</a>
        </div>
    </form>
</div>

</body>
</html>
