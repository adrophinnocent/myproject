<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Twinasafaris') }} - Admin Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                    },
                    safari: { dark:'#052010', mid:'#0a3d1f', light:'#1a9b50' },
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
        body { font-family: 'Outfit', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }

        .btn-brand {
            background: #1a9b50;
            color: white; font-weight:600;
            transition: all 0.3s;
        }
        .btn-brand:hover {
            background: #147a3e;
            transform: translateY(-2px);
            box-shadow:0 8px 25px rgba(26,155,80,0.3);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-safari-dark via-brand-900/10 to-safari-dark overflow-hidden">

    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-20 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-brand-900/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-4xl flex bg-white/10 rounded-3xl shadow-2xl overflow-hidden border border-white/10">

            <!-- Left Side - Decorative -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-brand-600 to-brand-900 p-12 flex-col justify-center items-center text-white">
                <div class="text-center">
                    <div class="w-32 h-32 mx-auto mb-8 bg-white/10 rounded-3xl flex items-center justify-center shadow-lg border border-white/20">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                    </div>
                    <h1 class="font-display text-4xl font-bold mb-4">{{ \App\Models\Setting::get('site_name', 'Twinasafaris') }}</h1>
                    <p class="text-brand-100 text-lg mb-6">Admin Dashboard</p>
                    <p class="text-sm text-brand-200/80">Welcome back! Manage your safari business with ease.</p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <div class="mb-8 md:hidden">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center overflow-hidden shadow-lg">
                            @if(\App\Models\Setting::get('logo'))
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Twinasafaris') }}" class="w-10 h-10 object-contain">
                            @else
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h1 class="font-display text-xl font-bold text-gray-900">{{ \App\Models\Setting::get('site_name', 'Twinasafaris') }}</h1>
                            <p class="text-sm text-gray-500">Admin Dashboard</p>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600 mb-8">Please sign in to access your admin panel</p>

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 0 9 9 0 009 0zm-4.5 7.5a2.5 2.5 0 005 0v-1.5" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="bellainnos@gmail.com" required
                                class="w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm"
                                placeholder="admin@example.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" value="password123" required
                                class="w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500">
                            <label for="remember" class="text-sm text-gray-600 cursor-pointer">Remember me</label>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full btn-brand py-3.5 rounded-xl text-base font-semibold flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Quick Login -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('emergency-login') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Quick auto-login for testing
                    </a>
                </div>

                <!-- Footer -->
                <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-brand-600 transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to website
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
