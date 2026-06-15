<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Figtree', sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #e6f9e6 0%, #d4f5d4 50%, #c0f0c0 100%);
            }

            .container {
                display: flex;
                width: 900px;
                max-width: 95%;
                background: white;
                border-radius: 25px;
                box-shadow: 0 25px 80px rgba(0, 100, 0, 0.15);
                overflow: hidden;
            }

            .left-side {
                flex: 1;
                padding: 60px 50px;
            }

            .right-side {
                flex: 1;
                background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%);
                padding: 60px 50px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .right-side::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
                animation: pulse 8s ease-in-out infinite;
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.5; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }

            h1 {
                font-size: 36px;
                font-weight: 700;
                color: #1a1a1a;
                margin-bottom: 10px;
            }

            .subtitle {
                color: #6b7280;
                margin-bottom: 35px;
                font-size: 15px;
            }

            .input-group {
                margin-bottom: 25px;
                position: relative;
            }

            .input-group label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
            }

            .input-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .input-icon {
                position: absolute;
                left: 18px;
                color: #9ca3af;
                font-size: 18px;
            }

            .input-group input {
                width: 100%;
                padding: 15px 18px 15px 48px;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                font-size: 15px;
                background: #f9fafb;
                transition: all 0.3s ease;
            }

            .input-group input:focus {
                outline: none;
                border-color: #22c55e;
                background: white;
                box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
            }

            .remember-forgot {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
                font-size: 14px;
            }

            .remember-me {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .remember-me input[type="checkbox"] {
                width: 18px;
                height: 18px;
                accent-color: #22c55e;
                cursor: pointer;
            }

            .remember-me label {
                color: #4b5563;
                cursor: pointer;
                font-size: 14px;
            }

            .forgot-password {
                color: #22c55e;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .forgot-password:hover {
                color: #16a34a;
            }

            .btn {
                width: 100%;
                padding: 16px;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-primary {
                background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
                color: white;
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s ease;
            }

            .btn-primary:hover::before {
                left: 100%;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(34, 197, 94, 0.35);
            }

            .btn-outline {
                background: transparent;
                color: white;
                border: 2px solid white;
            }

            .btn-outline::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background: white;
                transition: width 0.3s ease;
                z-index: 0;
            }

            .btn-outline:hover::before {
                width: 100%;
            }

            .btn-outline:hover {
                color: #22c55e;
            }

            .btn-outline span {
                position: relative;
                z-index: 1;
            }

            .right-side h2 {
                font-size: 32px;
                font-weight: 700;
                margin-bottom: 15px;
                text-align: center;
                position: relative;
                z-index: 1;
            }

            .right-side p {
                font-size: 15px;
                color: rgba(255,255,255,0.9);
                margin-bottom: 30px;
                text-align: center;
                position: relative;
                z-index: 1;
            }

            .social-login {
                margin-top: 30px;
                text-align: center;
            }

            .social-login p {
                color: #9ca3af;
                font-size: 13px;
                margin-bottom: 18px;
            }

            .social-icons {
                display: flex;
                gap: 15px;
                justify-content: center;
            }

            .social-icon {
                width: 45px;
                height: 45px;
                border: 2px solid #e5e7eb;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                color: #374151;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .social-icon:hover {
                border-color: #22c55e;
                background: #f0fdf4;
                transform: translateY(-2px);
            }

            .validation-error {
                color: #ef4444;
                font-size: 13px;
                margin-top: 6px;
            }

            @media (max-width: 768px) {
                .container {
                    flex-direction: column;
                    width: 95%;
                }

                .left-side, .right-side {
                    padding: 40px 30px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="left-side">
                <h1>Login</h1>
                <p class="subtitle">Welcome back! Please enter your details</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="input-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="validation-error" />
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="validation-error" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input id="remember_me" type="checkbox" name="remember">
                            <label for="remember_me">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="social-login">
                    <p>or login with social platforms</p>
                    <div class="social-icons">
                        <div class="social-icon">G</div>
                        <div class="social-icon">f</div>
                        <div class="social-icon">in</div>
                    </div>
                </div>
            </div>

            <div class="right-side">
                <h2>New Here?</h2>
                <p>Sign up and explore amazing features</p>
                <a href="{{ route('register') }}" class="btn btn-outline" style="text-decoration: none; width: 60%; text-align: center; padding: 14px;">
                    <span>Sign Up</span>
                </a>
            </div>
        </div>
    </body>
</html>
