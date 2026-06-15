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
    <link   rel="canonical"  href="{{ url()->current() }}">

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
    <script defer src="https://unpkg.com/{{ '@' }}alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs{{ '@' }}3.x.x/dist/cdn.min.js"></script>

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
