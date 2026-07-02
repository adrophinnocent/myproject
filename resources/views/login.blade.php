<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twinasafaris - Admin Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        50:  '#edfaf3',
                        100: '#c8f5da',
                        200: '#9aecb8',
                        300: '#5dd98a',
                        400: '#28c268',
                        500: '#1a9b50',
                        600: '#147a3e',
                        700: '#0f5a2e',
                        800: '#0a3d1f',
                        900: '#052010',
                    }
                },
                fontFamily: {
                    display: ['"Outfit"','system-ui','sans-serif'],
                    body:    ['"Outfit"','system-ui','sans-serif'],
                }
            }
        }
    }
    </script>

    <style>
        :root {
            --clay-bg: #ffffff;
            --clay-shadow-out: 20px 20px 60px #d1d9e6, -20px -20px 60px #ffffff;
            --clay-shadow-in: inset 6px 6px 12px rgba(0,0,0,0.05), inset -6px -6px 12px rgba(255,255,255,0.8);
            --clay-shadow-in-dark: inset 6px 6px 12px rgba(0,0,0,0.2), inset -6px -6px 12px rgba(255,255,255,0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f0f4f8;
            background-image: radial-gradient(at 0% 0%, rgba(26, 155, 80, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(26, 155, 80, 0.05) 0px, transparent 50%);
        }

        .clay-container {
            background: var(--clay-bg);
            border-radius: 50px;
            box-shadow: var(--clay-shadow-out);
            position: relative;
            border: 1px solid rgba(255,255,255,0.4);
        }

        .clay-container::after {
            content: '';
            position: absolute;
            top: 15px; left: 15px; right: 15px; bottom: 15px;
            border-radius: 40px;
            pointer-events: none;
            box-shadow: var(--clay-shadow-in);
        }

        .clay-input {
            background: #f8fafc;
            border: none;
            box-shadow: inset 4px 4px 8px #d1d9e6, inset -4px -4px 8px #ffffff;
            transition: all 0.3s ease;
        }

        .clay-input:focus {
            box-shadow: inset 2px 2px 4px #d1d9e6, inset -2px -2px 4px #ffffff;
            background: #ffffff;
        }

        .clay-button {
            box-shadow: 8px 8px 20px rgba(26, 155, 80, 0.2), inset 4px 4px 8px rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .clay-button:hover {
            transform: scale(0.98);
            box-shadow: 4px 4px 10px rgba(26, 155, 80, 0.2);
        }

        .clay-side-dark {
            box-shadow: var(--clay-shadow-in-dark);
            position: relative;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl flex flex-col md:flex-row clay-container overflow-hidden">

        <!-- Left Side - Decorative -->
        <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-brand-600 to-brand-900 p-12 flex-col justify-center items-center text-white clay-side-dark z-10">
            <div class="text-center animate-float">
                <div class="w-28 h-28 mx-auto mb-8 bg-white/10 rounded-[35px] flex items-center justify-center shadow-2xl border border-white/20 backdrop-blur-md">
                    <span class="text-5xl">🦁</span>
                </div>
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Twinasafaris</h1>
                <p class="text-brand-100 text-lg font-medium mb-2 uppercase tracking-widest">Admin Dashboard</p>
                <div class="w-12 h-1.5 bg-brand-400 mx-auto mb-6 rounded-full"></div>
                <p class="text-sm text-brand-200/80 leading-relaxed max-w-[250px]">Welcome back! Manage your safari business with ease.</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-7/12 p-8 md:p-16 z-10">
            <div class="mb-10 md:hidden text-center">
                <div class="w-16 h-16 bg-brand-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-3xl">🦁</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">Twinasafaris Admin</h1>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-500 font-medium">Please sign in to access your admin panel</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-7">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3 ml-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 0 9 9 0 009 0zm-4.5 7.5a2.5 2.5 0 005 0v-1.5" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="bellainnos@gmail.com" required
                            class="w-full pl-14 pr-6 py-4.5 rounded-[22px] clay-input focus:outline-none transition-all text-gray-800 font-medium"
                            placeholder="bellainnos@gmail.com" style="padding-top: 1.125rem; padding-bottom: 1.125rem;">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3 ml-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" value="password123" required
                            class="w-full pl-14 pr-6 py-4.5 rounded-[22px] clay-input focus:outline-none transition-all text-gray-800 font-medium"
                            placeholder="••••••••" style="padding-top: 1.125rem; padding-bottom: 1.125rem;">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center px-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" id="remember" name="remember" class="w-5 h-5 opacity-0 absolute cursor-pointer">
                            <div class="w-5 h-5 bg-white rounded-md clay-input flex items-center justify-center border-none transition-all group-hover:scale-110">
                                <div class="w-2.5 h-2.5 bg-brand-500 rounded-sm opacity-0 check-mark"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-600 font-bold group-hover:text-brand-600 transition-colors">Remember me</span>
                    </label>
                    <style>input:checked + div .check-mark { opacity: 1 !important; }</style>
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-brand-500 to-brand-600 py-4.5 rounded-[22px] text-white font-extrabold text-lg uppercase tracking-wider clay-button flex items-center justify-center gap-3" style="padding-top: 1.125rem; padding-bottom: 1.125rem;">
                    <span>Sign In</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-12 flex flex-col items-center gap-5">
                <a href="{{ route('emergency-login') }}" class="w-full py-3.5 bg-gray-200/50 rounded-2xl text-sm font-bold text-gray-600 hover:text-brand-600 transition-all text-center clay-input">
                    Quick auto-login for testing
                </a>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to website
                </a>
            </div>
        </div>
    </div>

</body>
</html>
