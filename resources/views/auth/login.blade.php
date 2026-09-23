<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pá-pum | Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-container { background: white; width: 100%; max-width: 360px; padding: 24px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .logo-box { text-align: center; margin-bottom: 20px; }
        .logo-text { font-size: 28px; font-weight: 800; color: #4f46e5; text-decoration: none; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }
        .form-group { margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 13px; font-weight: 600; color: #475569; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background-color: #f8fafc; }
        input:focus { outline: none; border-color: #4f46e5; background-color: white; }
        .remember-box { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 16px; }
        .btn-entrar { width: 100%; background-color: #4f46e5; color: white; border: none; padding: 12px; font-size: 15px; font-weight: bold; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .footer-links { text-align: center; margin-top: 16px; display: flex; flex-direction: column; gap: 8px; font-size: 13px; }
        .footer-links a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .error-message { color: #ef4444; font-size: 12px; margin-top: 4px; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo-box">
        <a href="/" class="logo-text">Pá-pum</a>
        <p class="subtitle">Seja bem-vindo de volta</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label id="label-email" for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label id="label-password" for="password">Senha</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            @error('password') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="remember-box">
            <input type="checkbox" id="remember_me" name="remember">
            <label id="label-remember_me" for="remember_me" style="font-weight: normal; cursor: pointer;">Lembrar de mim</label>
        </div>

        <button type="submit" class="btn-entrar">Entrar</button>

        <div class="footer-links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
            @endif
            <a href="{{ route('register') }}">Não tem uma conta? Cadastre-se</a>
        </div>
    </form>
</div>

</body>
</html>
