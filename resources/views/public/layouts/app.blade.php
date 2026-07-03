<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', \App\Models\Setting::get('seo_title', config('app.name', 'Twina Safaris')))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_description', 'Explore Africa\'s finest safari experiences with Twina Safaris.'))">
    <meta name="keywords"    content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords', 'safari, Tanzania, Africa, tours, Serengeti, Kilimanjaro, Zanzibar'))">
    <meta name="robots"      content="index, follow">
    <meta name="google-site-verification" content="3SbowPIqEdIG3r0Vkoq-q2OlJo7TuY9egyJFzYFZiyk" />
    <link   rel="canonical"  href="{{ url()->current() }}">

    {{-- Favicon --}}
    @if(\App\Models\Setting::get('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="@yield('title', \App\Models\Setting::get('seo_title', config('app.name')))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('meta_description'))">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Twitter --}}
    <meta property="twitter:card"    content="summary_large_image">
    <meta property="twitter:url"     content="{{ url()->current() }}">
    <meta property="twitter:title"   content="@yield('title', config('app.name'))">
    <meta property="twitter:description" content="@yield('meta_description')">
    <meta property="twitter:image"   content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Structured Data (Schema.org) --}}
    <script type="application/ld+json">
    {
      "{{ '@' }}context": "https://schema.org",
      "{{ '@' }}type": "TravelAgency",
      "name": "Twina Safaris",
      "alternateName": "Twina Safaris Tanzania",
      "url": "{{ url('/') }}",
      "logo": "{{ \App\Models\Setting::get('logo') ? asset('storage/' . \App\Models\Setting::get('logo')) : asset('images/logo.png') }}",
      "contactPoint": {
        "{{ '@' }}type": "ContactPoint",
        "telephone": "{{ \App\Models\Setting::get('site_phone') }}",
        "contactType": "customer service",
        "areaServed": "TZ",
        "availableLanguage": ["en", "Swahili"]
      },
      "sameAs": [
        "{{ \App\Models\Setting::get('facebook_url', '#') }}",
        "{{ \App\Models\Setting::get('instagram_url', '#') }}",
        "{{ \App\Models\Setting::get('youtube_url', '#') }}"
      ]
    }
    </script>

    {{-- Analytics & Pixels --}}
    @php
        $gaId = \App\Models\Setting::get('google_analytics_id');
        $fbPixel = \App\Models\Setting::get('facebook_pixel_id');
    @endphp

    @if($gaId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '{{ $gaId }}');
        </script>
    @endif

    @if($fbPixel)
        <script>
          !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
          n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
          document,'script','https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '{{ $fbPixel }}');
          fbq('track', 'PageView');
        </script>
    @endif

    @if(\App\Models\Setting::get('recaptcha_site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    {{-- Fonts & CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    gold: { 50:'#fdfbf0',100:'#faf3cc',200:'#f4e48a',300:'#eccf4a',400:'#e3b81a',500:'#D4AF37',600:'#b8920d',700:'#8f6e0a',800:'#704f0f',900:'#5a3f12' },
                    safari: { dark:'#0a0703',mid:'#1a1209',light:'#8B6914' },
                },
                fontFamily: {
                    display: ['"Cormorant Garamond"', 'serif'],
                    body: ['Inter', 'sans-serif'],
                }
            }
        }
    }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .nav-scrolled { background: rgba(10, 7, 3, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(212, 175, 55, 0.1); shadow: 0 4px 30px rgba(0,0,0,0.3); }
        .btn-gold { background: linear-gradient(135deg,#D4AF37,#b8920d); color:#1a1209; transition: all 0.3s; }
        .btn-gold:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(212,175,55,0.4); }
        .btn-outline-gold { border:2px solid #D4AF37; color:#D4AF37; transition: all 0.3s; }
        .btn-outline-gold:hover { background:#D4AF37; color:#ffffff; }
    </style>

    @yield('styles')
</head>
<body class="bg-white text-gray-900 font-body">

    <!-- Navigation -->
    @include('public.partials.navigation')

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Floating WhatsApp & AI Chat -->
    <div x-data="{
        isChatOpen: false,
        isAiOpen: false,
        messages: [],
        userInput: '',
        isLoading: false,
        async sendMessage() {
            if (!this.userInput.trim() || this.isLoading) return;

            const message = this.userInput;
            this.messages.push({ text: message, from: 'user' });
            this.userInput = '';
            this.isLoading = true;

            // Scroll to bottom
            this.$nextTick(() => {
                const el = document.getElementById('ai-chat-messages');
                el.scrollTop = el.scrollHeight;
            });

            try {
                const response = await fetch('{{ route('ai-assistant.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message,
                        history: this.messages.slice(0, -1)
                    })
                });

                const data = await response.json();
                this.messages.push({ text: data.response, from: 'bot' });
            } catch (error) {
                this.messages.push({ text: 'Jambo! I had a connection issue. Please try again.', from: 'bot' });
            } finally {
                this.isLoading = false;
                this.$nextTick(() => {
                    const el = document.getElementById('ai-chat-messages');
                    el.scrollTop = el.scrollHeight;
                });
            }
        }
    }" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 items-end">
        <!-- AI Chat Widget -->
        <div x-show="isAiOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="w-80 bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 mb-2">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Twina AI Assistant</h3>
                            <p class="text-xs text-white/80">Expert Safari Guide</p>
                        </div>
                    </div>
                    <button @click="isAiOpen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-80 overflow-y-auto p-4 space-y-3 bg-gray-50" id="ai-chat-messages">
                <div class="bg-white p-3 rounded-xl rounded-tl-none shadow-sm max-w-[85%] border border-gray-100">
                    <p class="text-sm text-gray-700 font-medium">Jambo! I'm your Twina Safaris guide. How can I help you today?</p>
                </div>
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.from === 'user' ? 'ml-auto text-right' : 'mr-auto text-left'">
                        <div :class="msg.from === 'user'
                            ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3 rounded-xl rounded-tr-none shadow-sm inline-block text-left max-w-[90%]'
                            : 'bg-white text-gray-700 p-3 rounded-xl rounded-tl-none shadow-sm inline-block text-left max-w-[90%] border border-gray-100'">
                            <p class="text-sm leading-relaxed" x-text="msg.text"></p>
                        </div>
                    </div>
                </template>
                <div x-show="isLoading" class="mr-auto text-left" x-cloak>
                    <div class="bg-white text-gray-700 p-3 rounded-xl rounded-tl-none shadow-sm inline-block border border-gray-100">
                        <div class="flex gap-1">
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 bg-white shadow-[0_-5px_15px_rgba(0,0,0,0.02)]">
                <form @submit.prevent="sendMessage()" class="flex gap-2">
                    <input type="text"
                           x-model="userInput"
                           :disabled="isLoading"
                           placeholder="Ask about safaris, prices..."
                           class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent disabled:bg-gray-50 transition-all">
                    <button type="submit"
                            :disabled="isLoading || !userInput.trim()"
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-center hover:scale-105 transition-all disabled:opacity-50 disabled:scale-100 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- AI Chat Button -->
        <button @click="isAiOpen = !isAiOpen; isChatOpen = false"
                class="w-14 h-14 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-xl flex items-center justify-center hover:scale-110 transition-all ring-4 ring-white/50">
            <svg x-show="!isAiOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"></path>
            </svg>
            <svg x-show="isAiOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- WhatsApp Button -->
        <a href="https://wa.me/255795482197?text=Hello%20Twina%20Safaris%2C%20I%27m%20interested%20in%20your%20tours!"
           target="_blank"
           class="w-14 h-14 rounded-full bg-[#25D366] text-white shadow-xl flex items-center justify-center hover:scale-110 hover:bg-[#20ba56] transition-all ring-4 ring-white/50 group">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
    </div>

    <!-- Footer -->
    @include('public.partials.footer')

    <!-- Global Scripts -->
    <script>
    const nav = document.getElementById('main-nav');
    const isHome = {{ request()->routeIs('home') ? 'true' : 'false' }};

    function updateNavbar() {
        if (!nav) return;
        if (window.scrollY > 60 || !isHome) {
            nav.classList.add('nav-scrolled');
            nav.classList.remove('nav-transparent');
        } else {
            nav.classList.remove('nav-scrolled');
            nav.classList.add('nav-transparent');
        }
    }
    window.addEventListener('scroll', updateNavbar, { passive: true });
    updateNavbar();

    // Event Tracking Helpers
    function trackWhatsAppClick(tour) {
        if (typeof gtag !== 'undefined') gtag('event', 'whatsapp_click', { 'tour_name': tour });
        if (typeof fbq !== 'undefined') fbq('track', 'Contact', { 'method': 'whatsapp' });
    }
    </script>

    @yield('scripts')
</body>
</html>
