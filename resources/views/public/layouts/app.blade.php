<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', \App\Models\Setting::get('seo_title', config('app.name', 'Twina Safaris')))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_description', 'Explore Africa\'s finest safari experiences with Twina Safaris.'))">
    <meta name="keywords"    content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords', 'safari, Tanzania, Africa, tours, Serengeti, Kilimanjaro, Zanzibar'))">
    <meta name="google-site-verification" content="3SbowPIqEdIG3r0Vkoq-q2OlJo7TuY9egyJFzYFZiyk" />

    @php
        $seoLocale = \App\Helpers\SeoHelper::defaultLocale();
        $canonical = \App\Helpers\SeoHelper::canonicalUrl(request());

        // Booking policies are public trust content, unlike the booking flow.
        $isPrivateBooking = request()->is('booking', 'booking/*') && ! request()->is('booking/policies');

        // Filtered tour listings duplicate the clean /tours page. Keep links
        // followable so crawlers still find the canonical listing.
        $isFilteredListing = request()->is('tours') && collect(array_keys(request()->query()))
            ->contains(fn ($key) => ! in_array($key, ['page', 'lang'], true));

        $isNoindex = request()->is('admin', 'admin/*', 'my-bookings', 'trip-plan', 'trip-plan/*', 'proposal', 'proposal/*', 'offer', 'offer/*', 'login', 'forgot-password', 'reset-password', 'reset-password/*')
            || $isPrivateBooking
            || request()->is('tours/*/book')
            || $isFilteredListing;
    @endphp
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Meta Robots: private flows and filters stay out of the index --}}
    @hasSection('meta_robots')
        <meta name="robots" content="@yield('meta_robots')">
    @elseif($isNoindex)
        <meta name="robots" content="{{ $isFilteredListing ? 'noindex, follow, noarchive, nosnippet' : 'noindex, nofollow, noarchive, nosnippet' }}">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://connect.facebook.net">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    {{-- Favicon --}}
    @php $favicon = \App\Helpers\AssetHelper::getFaviconUrl(); @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#D4AF37">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:url"         content="{{ $canonical }}">
    <meta property="og:site_name"   content="{{ \App\Models\Setting::get('site_name', 'Twina Safaris') }}">
    <meta property="og:title"       content="@yield('title', \App\Models\Setting::get('seo_title', config('app.name')))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('meta_description'))">
    <meta property="og:image"       content="@yield('og_image', \App\Helpers\AssetHelper::getOgImageUrl())">
    <meta property="og:image:alt"   content="@yield('og_image_alt', 'Twina Safaris safari and trekking in Tanzania')">
    <meta property="og:locale"      content="{{ \App\Helpers\SeoHelper::ogLocale($seoLocale) }}">
    @yield('article_meta')

    {{-- Twitter --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:url"         content="{{ $canonical }}">
    <meta name="twitter:title"       content="@yield('title', \App\Models\Setting::get('seo_title', config('app.name')))">
    <meta name="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('meta_description'))">
    <meta name="twitter:image"       content="@yield('og_image', \App\Helpers\AssetHelper::getOgImageUrl())">
    <meta name="twitter:image:alt"   content="@yield('og_image_alt', 'Twina Safaris safari and trekking in Tanzania')">
    <meta name="twitter:site"        content="@TwinaSafaris">

    {{-- Structured Data (Schema.org) for AI & Google --}}
    @php
        $siteUrl = \App\Helpers\SeoHelper::siteUrl();
        $sameAs = array_values(array_filter([
            \App\Models\Setting::get('facebook_url'),
            \App\Models\Setting::get('instagram_url'),
            \App\Models\Setting::get('youtube_url'),
            \App\Models\Setting::get('twitter_url'),
            \App\Models\Setting::get('tiktok_url'),
        ], fn ($url) => is_string($url) && str_starts_with($url, 'http')));

        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            '@id' => $siteUrl.'/#organization',
            'name' => \App\Models\Setting::get('site_name', 'Twina Safaris'),
            'alternateName' => 'Twina Safaris Tanzania',
            'url' => $siteUrl.'/',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => \App\Helpers\AssetHelper::getLogoUrl(),
            ],
            'image' => \App\Helpers\AssetHelper::getOgImageUrl(),
            'description' => \App\Models\Setting::get('meta_description', 'Safari and trekking adventures in Tanzania with local expert guides.'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => \App\Models\Setting::get('address_street', 'Moshi'),
                'addressLocality' => \App\Models\Setting::get('address_locality', 'Kilimanjaro'),
                'addressRegion' => \App\Models\Setting::get('address_region', 'Kilimanjaro'),
                'postalCode' => \App\Models\Setting::get('postal_code', '25101'),
                'addressCountry' => \App\Models\Setting::get('address_country', 'TZ'),
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => \App\Models\Setting::get('latitude', '-3.3444'),
                'longitude' => \App\Models\Setting::get('longitude', '37.3344'),
            ],
            'telephone' => \App\Models\Setting::get('site_phone'),
            'email' => \App\Models\Setting::publicEmail(),
            'sameAs' => $sameAs,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => \App\Models\Setting::get('site_phone'),
                'contactType' => 'customer service',
                'areaServed' => 'Worldwide',
                'availableLanguage' => ['English', 'German', 'French', 'Spanish'],
            ],
        ];

        $webSiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $siteUrl.'/#website',
            'url' => $siteUrl.'/',
            'name' => \App\Models\Setting::get('site_name', 'Twina Safaris'),
            'publisher' => ['@id' => $siteUrl.'/#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $siteUrl.'/tours?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    @endphp
    <script type="application/ld+json">
    @json([$organizationSchema, $webSiteSchema], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
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

    {{-- Custom Header Scripts --}}
    {!! \App\Models\Setting::get('header_scripts') !!}

    {{-- SEO Schema --}}
    @yield('schema')

    {{-- Fonts & CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    </noscript>

    {{-- Built assets replace the render-blocking Tailwind CDN compiler and
         three separate Alpine CDN requests. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        :is(a, button, input, select, textarea, summary, [tabindex]):focus-visible {
            outline: 2px solid #b8920d !important;
            outline-offset: 2px;
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
        .nav-transparent { background: linear-gradient(180deg, rgba(10, 7, 3, 0.85) 0%, rgba(10, 7, 3, 0.3) 70%, transparent 100%); transition: all 0.4s ease; }
        .nav-scrolled { background: rgba(10, 7, 3, 0.96) !important; backdrop-filter: blur(20px); border-bottom: 1px solid rgba(212, 175, 55, 0.2); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5); transition: all 0.4s ease; }
        .btn-gold { background: linear-gradient(135deg,#D4AF37,#8A6D0B); color:#1a1209; transition: all 0.3s; }
        .btn-gold:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(212,175,55,0.4); }
        .btn-outline-gold { border:2px solid #8A6D0B; color:#8A6D0B; transition: all 0.3s; }
        .btn-outline-gold:hover { background:#8A6D0B; color:#ffffff; }
        .text-gold-600 { color: #8A6D0B !important; }
        {{-- Preserve the exact shades used by the former CDN config. --}}
        .bg-gold-600 { background-color: #8A6D0B !important; }
        .to-gold-600 { --tw-gradient-to: #8A6D0B !important; }
        .text-gold-700 { color: #704f0f !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Blog article system: one rule for every article photo + all block styles. --}}
    <style>
        /* Hero and card images always render in 3:2, whatever the source file is. */
        .article-hero img,
        .blog-card-image,
        .more-read-image {
            aspect-ratio: 3 / 2;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        .article-hero img {
            width: 100%;
            height: min(66.6667vw, 620px);
            max-height: 620px;
            object-fit: cover;
            object-position: center;
        }

        /* Every image inside article content is 3:2. Admin never sets this. */
        .article-body figure,
        .article-body img,
        .article-body figure img,
        .article-body p img {
            aspect-ratio: 3 / 2 !important;
            object-fit: cover !important;
            object-position: center !important;
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
            max-height: none !important;
            display: block;
            margin: 0;
            border-radius: 1rem;
        }
        .article-body figure {
            margin: 2rem 0;
            background: #faf9f6;
            border: 1px solid #e7e5e4;
            padding: .5rem;
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .article-body figcaption {
            margin-top: .65rem;
            font-size: .75rem;
            font-style: italic;
            font-weight: 500;
            color: #78716c;
            text-align: center;
            padding: 0 .5rem .25rem;
        }

        /* Article typography */
        .article-body {
            max-width: 760px;
            margin: 0 auto;
            color: #292524;
            font-family: Inter, sans-serif;
            font-size: 1rem;
            line-height: 1.85;
            overflow-wrap: break-word;
        }
        .article-body p { margin: 0 0 1.25rem; }
        .article-body .article-heading-lg,
        .article-body h2 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 2rem;
            font-weight: 700;
            color: #0d2818;
            line-height: 1.2;
            margin: 2.5rem 0 1rem;
            padding-bottom: .6rem;
            border-bottom: 2px solid #f1f0ea;
        }
        .article-body .article-heading-sm,
        .article-body h3 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #0d2818;
            margin: 2rem 0 .75rem;
        }
        @media (min-width: 768px) {
            .article-body .article-heading-lg, .article-body h2 { font-size: 2.35rem; }
        }
        .article-body a { color: #8A6D0B; text-decoration: underline; text-underline-offset: 3px; }
        .article-body strong { color: #0d2818; }
        .article-body ul, .article-body ol { padding-left: 1.25rem; margin: 1.25rem 0; }
        .article-body li { margin-bottom: .4rem; }

        /* Check list and bullet list: 2 columns on desktop, 1 on mobile. */
        .article-body .article-check-list,
        .article-body .article-bullet-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem 1.5rem;
            margin: 1.5rem 0;
        }
        .article-body .article-check-list li,
        .article-body .article-bullet-list li {
            position: relative;
            padding-left: 1.85rem;
            margin: 0;
            line-height: 1.5;
        }
        .article-body .article-check-list li::before,
        .article-body .article-bullet-list li::before {
            position: absolute;
            left: 0;
            top: 0;
            font-weight: 800;
        }
        .article-body .article-check-list li::before { content: '✓'; color: #D4AF37; }
        .article-body .article-bullet-list li::before { content: '•'; color: #8A6D0B; font-size: 1.2rem; line-height: 1; }
        @media (max-width: 640px) {
            .article-body .article-check-list,
            .article-body .article-bullet-list { grid-template-columns: 1fr; gap: .5rem; }
        }

        /* Numbered steps */
        .article-body .article-steps { list-style: none; padding: 0; display: grid; gap: 1rem; margin: 1.5rem 0; }
        .article-body .article-steps li { position: relative; padding-left: 3.25rem; margin: 0; }
        .article-body .article-step-num {
            position: absolute;
            left: 0;
            top: -.15rem;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #D4AF37, #8A6D0B);
            color: #1a1209;
            font-weight: 800;
            font-size: .85rem;
            display: grid;
            place-items: center;
            box-shadow: 0 4px 12px rgba(212,175,55,.3);
        }

        /* Quote */
        .article-body .article-quote {
            border-left: 4px solid #D4AF37;
            background: linear-gradient(135deg, #fcfaf7, #f6eedb);
            padding: 1.35rem 1.6rem;
            margin: 2rem 0;
            border-radius: 0 1rem 1rem 0;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.5rem;
            font-style: italic;
            line-height: 1.4;
            color: #44403c;
        }
        .article-body .article-quote p { margin: 0; }
        .article-body .article-quote footer {
            margin-top: .75rem;
            font-family: Inter, sans-serif;
            font-size: .72rem;
            font-style: normal;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #8A6D0B;
        }

        /* Tip / Warning / Info callouts */
        .article-body .article-note {
            border-radius: 1rem;
            padding: 1.1rem 1.35rem;
            margin: 1.75rem 0;
            border: 1px solid;
            border-left-width: 4px;
        }
        .article-body .article-note strong {
            display: block;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .4rem;
        }
        .article-body .article-note p:last-child { margin-bottom: 0; }
        .article-body .article-note--tip { background: #f0fdf4; border-color: #86efac; color: #14532d; }
        .article-body .article-note--tip strong { color: #15803d; }
        .article-body .article-note--warning { background: #fffbeb; border-color: #fcd34d; color: #78350f; }
        .article-body .article-note--warning strong { color: #b45309; }
        .article-body .article-note--info { background: #eff6ff; border-color: #93c5fd; color: #1e3a8a; }
        .article-body .article-note--info strong { color: #1d4ed8; }

        /* FAQ accordion */
        .article-body .article-faq {
            border: 1px solid #e7e5e4;
            border-radius: 1rem;
            background: #fff;
            margin: .85rem 0;
            overflow: hidden;
        }
        .article-body .article-faq summary {
            cursor: pointer;
            list-style: none;
            padding: 1rem 3rem 1rem 1.15rem;
            font-weight: 700;
            color: #0d2818;
            position: relative;
        }
        .article-body .article-faq summary::-webkit-details-marker { display: none; }
        .article-body .article-faq summary::after {
            content: '+';
            position: absolute;
            right: 1.15rem;
            top: .75rem;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 9999px;
            background: #f6eedb;
            color: #8A6D0B;
            font-weight: 800;
            display: grid;
            place-items: center;
        }
        .article-body .article-faq[open] summary::after { content: '−'; }
        .article-body .article-faq-answer { padding: 0 1.15rem 1rem; color: #57534e; }
        .article-body .article-faq-answer p:last-child { margin-bottom: 0; }

        /* CTA block (works both inside the article and at the end of it) */
        .article-cta {
            background: linear-gradient(135deg, #0d2818, #051610);
            color: #fff;
            border: 1px solid rgba(212,175,55,.35);
            border-radius: 1.5rem;
            padding: 1.75rem;
            margin: 2.5rem 0;
            text-align: center;
        }
        .article-cta h3 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.75rem;
            color: #fff;
            margin: 0 0 .5rem;
        }
        .article-cta p { color: rgba(255,255,255,.75); margin-bottom: 1.25rem; }
        .article-cta .btn-gold {
            display: inline-block;
            border-radius: 9999px;
            padding: .8rem 1.75rem;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
        }

        /* Decorative divider */
        .article-body .article-divider {
            text-align: center;
            color: #D4AF37;
            letter-spacing: .6em;
            font-size: 1.1rem;
            margin: 2.5rem 0;
        }
    </style>

    @yield('styles')
</head>
<body class="bg-white text-gray-900 font-body" itemscope itemtype="https://schema.org/WebPage">

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-safari-dark focus:ring-2 focus:ring-gold-500">
        {{ __('Skip to main content') }}
    </a>

    <!-- Navigation -->
    @include('public.partials.navigation')

    <!-- Main Content -->
    <main id="main-content" itemprop="mainContentOfPage">
        @yield('content')
    </main>

    <!-- Floating WhatsApp & AI Chat -->
    <div x-data="{
        showWidgets: false,
        isChatOpen: false,
        isAiOpen: false,
        messages: [],
        userInput: '',
        isLoading: false,
        init() {
            setTimeout(() => { this.showWidgets = true; }, 3000);
        },
        async sendMessage() {
            if (!this.userInput.trim() || this.isLoading) return;

            const message = this.userInput;
            this.messages.push({ text: message, from: 'user' });
            this.userInput = '';
            this.isLoading = true;

            // Scroll to bottom
            this.$nextTick(() => {
                const el = document.getElementById('ai-chat-messages');
                if (el) el.scrollTop = el.scrollHeight;
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
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        }
    }"
    x-show="showWidgets"
    x-cloak
    role="region"
    aria-label="{{ __('Safari assistance widgets') }}"
    class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 items-end">
        <!-- AI Chat Widget -->
        <div x-show="isAiOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             role="region"
              aria-label="{{ __('Twina AI Assistant chat') }}"
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
                            <h3 class="font-semibold">{{ __('Twina AI Assistant') }}</h3>
                            <p class="text-xs text-white/80">{{ __('Expert Safari Guide') }}</p>
                        </div>
                    </div>
                    <button @@click="isAiOpen = false" aria-label="{{ __('Close chat') }}" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-80 overflow-y-auto p-4 space-y-3 bg-gray-50" id="ai-chat-messages" role="log" aria-live="polite" aria-relevant="additions">
                <div class="bg-white p-3 rounded-xl rounded-tl-none shadow-sm max-w-[85%] border border-gray-100">
                    <p class="text-sm text-gray-700 font-medium">{{ __('Jambo! I\'m your Twina Safaris guide. How can I help you today?') }}</p>
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
                <form @@submit.prevent="sendMessage()" class="flex gap-2">
                    <input type="text"
                           x-model="userInput"
                           :disabled="isLoading"
                           aria-label="{{ __('Ask the safari assistant a question') }}"
                           placeholder="{{ __('Ask about safaris, prices...') }}"
                           class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent disabled:bg-gray-50 transition-all">
                    <button type="submit"
                            :disabled="isLoading || !userInput.trim()"
                            aria-label="{{ __('Send message') }}"
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-center hover:scale-105 transition-all disabled:opacity-50 disabled:scale-100 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- AI Chat Button -->
        <button @@click="isAiOpen = !isAiOpen; isChatOpen = false"
                aria-label="{{ __('Toggle AI safari assistant') }}"
                :aria-expanded="isAiOpen.toString()"
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
           rel="noopener noreferrer"
           aria-label="{{ __('Chat with us on WhatsApp') }}"
           class="w-14 h-14 rounded-full bg-[#25D366] text-white shadow-xl flex items-center justify-center hover:scale-110 hover:bg-[#20ba56] transition-all ring-4 ring-white/50 group">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
    </div>

    {{-- GDPR Cookie Consent Banner --}}
    <div x-data="{ accepted: localStorage.getItem('cookieConsent') === 'accepted' }"
         x-show="!accepted" x-cloak x-transition
         role="region"
         aria-label="{{ __('Cookie and privacy notice') }}"
         class="fixed bottom-4 left-4 right-4 md:left-6 md:right-auto md:max-w-md bg-[#0a0703] border border-gold-500/30 text-white p-5 rounded-2xl shadow-2xl z-50 flex flex-col gap-3">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-gold-500/20 text-gold-400 flex items-center justify-center shrink-0 text-sm font-bold">🍪</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gold-400">Cookie & Privacy Notice</p>
                <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                    We use cookies to analyze traffic and optimize your safari experience. Read our <a href="{{ route('privacy.index') }}" class="underline text-gold-400 font-semibold">Privacy Policy</a>.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 justify-end pt-1">
            <button @click="localStorage.getItem('cookieConsent') ? null : localStorage.setItem('cookieConsent', 'accepted'); accepted = true"
                    class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-safari-dark font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow">
                Accept Cookies
            </button>
        </div>
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

    {{-- Custom Footer Scripts --}}
    {!! \App\Models\Setting::get('footer_scripts') !!}

    @yield('scripts')
</body>
</html>
