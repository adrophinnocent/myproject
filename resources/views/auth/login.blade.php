<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - Twina Safaris</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-color: #f0f4f8;
            --clay-bg: #ffffff;
            --clay-shadow-out: 20px 20px 60px #d1d9e6, -20px -20px 60px #ffffff;
            --clay-shadow-in: inset 6px 6px 12px rgba(0,0,0,0.05), inset -6px -6px 12px rgba(255,255,255,0.8);
            --primary: #D4AF37;
            --primary-dark: #b8920d;
            --text-main: #2d3748;
            --text-muted: #718096;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: var(--text-main);
        }

        .clay-card {
            background: var(--clay-bg);
            border-radius: 40px;
            box-shadow: var(--clay-shadow-out);
            padding: 50px;
            width: 100%;
            max-width: 480px;
            position: relative;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }

        /* Inner soft glow for clay effect */
        .clay-card::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border-radius: 32px;
            pointer-events: none;
            box-shadow: var(--clay-shadow-in);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-symbol {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 24px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff, inset 4px 4px 8px rgba(255,255,255,0.4);
            color: white;
            font-size: 32px;
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .welcome-text {
            color: var(--text-muted);
            font-size: 15px;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
            margin-left: 5px;
            color: var(--text-main);
        }

        .clay-input {
            width: 100%;
            background: #f8fafc;
            border: none;
            padding: 18px 24px;
            border-radius: 20px;
            font-family: inherit;
            font-size: 15px;
            color: var(--text-main);
            box-shadow: inset 4px 4px 8px #d1d9e6, inset -4px -4px 8px #ffffff;
            transition: all 0.3s ease;
            outline: none;
        }

        .clay-input:focus {
            box-shadow: inset 2px 2px 4px #d1d9e6, inset -2px -2px 4px #ffffff;
            background: #ffffff;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            padding: 0 5px;
            z-index: 1;
            position: relative;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: var(--text-muted);
        }

        .checkbox-label input {
            width: 20px;
            height: 20px;
            border-radius: 8px;
            accent-color: var(--primary);
        }

        .forgot-link {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .btn-clay {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 20px;
            border-radius: 22px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 8px 8px 20px #d1d9e6, inset 4px 4px 8px rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            text-transform: uppercase;
            tracking-wider: 1px;
            z-index: 1;
            position: relative;
        }

        .btn-clay:hover {
            transform: scale(0.98);
            box-shadow: 4px 4px 10px #d1d9e6;
        }

        .btn-clay:active {
            box-shadow: inset 4px 4px 8px rgba(0,0,0,0.1);
        }

        .footer-links {
            margin-top: 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1;
            position: relative;
        }

        .test-login {
            background: #e2e8f0;
            padding: 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            box-shadow: 4px 4px 8px #d1d9e6, -4px -4px 8px #ffffff;
        }

        .back-link {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .error-msg {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 8px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .clay-card {
                padding: 35px 25px;
                border-radius: 30px;
            }
            h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

    <div class="clay-card">
        <div class="logo-container">
            <div class="logo-symbol">
                🐘
            </div>
            <h1>Twina Safaris Admin</h1>
            <p class="welcome-text">Welcome back! Manage your safari business with ease.<br>Please sign in to access your admin panel.</p>
        </div>

        @if (session('status'))
            <div class="welcome-text" style="color: #48bb78; font-weight: bold; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" class="clay-input" type="email" name="email" value="{{ old('email', 'bellainnos@gmail.com') }}" required autofocus placeholder="email@example.com">
                @if($errors->has('email'))
                    <div class="error-msg">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" class="clay-input" type="password" name="password" required placeholder="••••••••">
                @if($errors->has('password'))
                    <div class="error-msg">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="options">
                <label for="remember_me" class="checkbox-label">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">Forgot?</a>
                @endif
            </div>

            <button type="submit" class="btn-clay">
                Sign In
            </button>
        </form>

        <div class="footer-links">
            <a href="#" onclick="document.getElementById('email').value='bellainnos@gmail.com'; document.getElementById('password').value='password'; return false;" class="test-login">
                Quick auto-login for testing
            </a>
            <a href="{{ route('home') }}" class="back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to website
            </a>
        </div>
    </div>

</body>
</html>
