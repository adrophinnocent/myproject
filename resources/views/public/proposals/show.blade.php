<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Twina Safaris — {{ $proposal->title }} | Private Proposal</title>

    {{-- Favicon --}}
    @if(\App\Models\Setting::get('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Tailwind CSS & Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --clr-forest: #052010;
            --clr-forest-mid: #0b381c;
            --clr-gold: #D4AF37;
            --clr-gold-light: #fbbf24;
            --clr-bg-light: #f4fdf7;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--clr-bg-light);
            color: #0a1f10;
        }
        .font-serif-title, .font-display {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        .bg-twina-forest {
            background-color: #052010;
        }
        .bg-twina-card {
            background-color: #0b381c;
        }
        .text-twina-gold {
            color: #D4AF37;
        }
        .border-twina-gold {
            border-color: rgba(212, 175, 55, 0.3);
        }
        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #f59e0b 100%);
            color: #052010;
            font-weight: 800;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(212, 175, 55, 0.5);
            background: linear-gradient(135deg, #f59e0b 0%, #D4AF37 100%);
        }
        .btn-outline-gold {
            border: 2px solid #D4AF37;
            color: #D4AF37;
            background: transparent;
            font-weight: 800;
            transition: all 0.3s ease;
        }
        .btn-outline-gold:hover {
            background: #D4AF37;
            color: #052010;
        }
        .btn-forest {
            background-color: #052010;
            color: #ffffff;
            border: 1px solid rgba(212, 175, 55, 0.4);
            font-weight: 800;
            transition: all 0.3s ease;
        }
        .btn-forest:hover {
            background-color: #0d3d20;
            border-color: #D4AF37;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen pb-28" x-data="{ acceptModal: false, changesModal: false, activeTab: 'itinerary', lightboxImg: null, mobileNavOpen: false }">

    {{-- TOP BRAND NAVIGATION BAR --}}
    <header class="bg-twina-forest border-b border-twina-gold/40 text-white sticky top-0 z-40 backdrop-blur-lg bg-opacity-95 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-xl md:text-2xl font-black tracking-widest font-serif-title text-twina-gold group-hover:text-amber-300 transition-colors">
                        TWINA SAFARIS
                    </span>
                </a>
                <div class="hidden md:flex items-center gap-2 pl-4 border-l border-twina-gold/30 text-xs font-bold text-amber-200/80 uppercase tracking-widest">
                    <span>Client Proposal</span>
                    <span class="text-twina-gold">&bull;</span>
                    <span class="text-twina-gold font-mono">{{ $proposal->full_reference }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank"
                   class="px-4 py-2.5 bg-emerald-700/30 hover:bg-emerald-700/50 text-emerald-300 border border-emerald-500/40 rounded-xl text-xs font-extrabold transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span class="hidden sm:inline">Contact Twina Safaris</span>
                    <span class="sm:hidden">Contact</span>
                </a>
                <a href="{{ route('proposal.pdf', $proposal->token) }}"
                   class="px-4 py-2.5 btn-outline-gold rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download PDF</span>
                </a>
            </div>
        </div>
    </header>

    {{-- FLASH NOTIFICATIONS --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="bg-emerald-900/10 border-2 border-emerald-600 text-emerald-900 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-xs font-bold">{!! session('success') !!}</div>
        </div>
    </div>
    @endif

    {{-- COVER / HERO SECTION --}}
    <section class="relative bg-twina-forest text-white overflow-hidden py-14 md:py-24 border-b border-twina-gold/40">
        @php
            $heroCover = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1920&q=80';
            if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0 && !empty($proposal->itinerary[0]['cover_image'])) {
                $heroCover = $proposal->itinerary[0]['cover_image'];
            }
        @endphp

        <div class="absolute inset-0 z-0 opacity-35 bg-cover bg-center transition-all duration-700" style="background-image: url('{{ $heroCover }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#052010] via-[#052010]/85 to-[#052010]/60 z-10"></div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="space-y-4 max-w-3xl">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-twina-gold/15 border border-twina-gold/40 rounded-full text-twina-gold text-xs font-extrabold uppercase tracking-widest backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-twina-gold animate-pulse"></span>
                        <span>Twina Safaris &bull; Private Proposal {{ $proposal->full_reference }}</span>
                    </div>

                    <p class="text-twina-gold font-display text-lg md:text-2xl font-semibold italic tracking-wide">
                        Your Personalized Tanzania Safari
                    </p>

                    <h1 class="text-3xl sm:text-4xl md:text-6xl font-black font-serif-title text-white leading-tight tracking-tight drop-shadow-lg">
                        {{ $proposal->title }}
                    </h1>

                    @if($proposal->subtitle)
                        <p class="text-amber-200/90 font-medium text-sm md:text-base leading-relaxed">{{ $proposal->subtitle }}</p>
                    @endif

                    <div class="pt-2 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-twina-gold/20 border border-twina-gold/40 flex items-center justify-center text-twina-gold font-serif-title font-bold text-lg">
                            {{ strtoupper(substr($proposal->client_name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-amber-200/70 uppercase tracking-widest block">Exclusively Prepared For</span>
                            <span class="text-sm md:text-base font-black text-white">{{ $proposal->client_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Investment Card --}}
                <div class="bg-[#0b381c]/90 border border-twina-gold/40 backdrop-blur-xl rounded-3xl p-6 text-left md:text-right shadow-2xl shrink-0 min-w-[280px]">
                    <div class="text-[10px] uppercase font-black text-twina-gold tracking-widest mb-1">YOUR SAFARI INVESTMENT</div>
                    <div class="text-3xl md:text-4xl font-black text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                    <div class="text-xs text-amber-100/90 font-medium mt-1">
                        For {{ $proposal->adults }} Adult(s) @if($proposal->children > 0) & {{ $proposal->children }} Child(ren) @endif
                    </div>
                    @if($proposal->adult_price)
                        <div class="text-[11px] text-twina-gold/90 font-bold mt-2 pt-2 border-t border-twina-gold/20">
                            {{ $proposal->currency_symbol }}{{ number_format($proposal->adult_price, 2) }} per adult
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN PROPOSAL CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8" id="proposal-content">

        {{-- ROUTE BANNER --}}
        <div class="bg-twina-forest text-white rounded-3xl p-5 md:p-6 mb-8 border border-twina-gold/40 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-twina-gold/20 border border-twina-gold/40 text-twina-gold flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-twina-gold tracking-widest">Your Tailored Safari Route</div>
                    <div class="text-sm md:text-base font-black text-white mt-0.5">{{ $proposal->route_chain }}</div>
                </div>
            </div>

            <div class="text-xs font-bold text-amber-200 bg-twina-gold/15 border border-twina-gold/40 px-4 py-2 rounded-xl self-start sm:self-auto">
                Quotation Valid Until: <strong class="text-twina-gold">{{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}</strong>
            </div>
        </div>

        {{-- TRIP OVERVIEW CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white p-5 rounded-3xl border border-emerald-900/10 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-900/10 text-[#052010] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#1a9b50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Travel Dates</div>
                    <div class="text-xs font-black text-[#052010] mt-0.5">
                        @if($proposal->start_date)
                            {{ $proposal->start_date->format('M d, Y') }}
                            @if($proposal->end_date) - {{ $proposal->end_date->format('M d, Y') }} @endif
                        @else
                            Flexible Dates
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-emerald-900/10 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-900/10 text-[#052010] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#1a9b50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Duration</div>
                    <div class="text-xs font-black text-[#052010] mt-0.5">{{ $proposal->duration_days }} Days / {{ $proposal->duration_nights }} Nights</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-emerald-900/10 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-900/10 text-[#052010] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#1a9b50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Travelers</div>
                    <div class="text-xs font-black text-[#052010] mt-0.5">{{ $proposal->adults }} Adult(s) @if($proposal->children > 0), {{ $proposal->children }} Child(ren) @endif</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-emerald-900/10 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Accommodation Level</div>
                    <div class="text-xs font-black uppercase text-[#D4AF37] mt-0.5">
                        {{ $proposal->accommodation_level ?: 'Luxury Comfort' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- SAFARI HIGHLIGHTS SUMMARY --}}
        @if(is_array($proposal->highlights) && count($proposal->highlights) > 0)
        <div class="mb-10 bg-white rounded-3xl p-6 md:p-8 border border-emerald-900/10 shadow-sm">
            <h3 class="text-xs font-black text-emerald-900/60 uppercase tracking-widest mb-4 font-serif-title">Key Safari Highlights & Experiences</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                @foreach($proposal->highlights as $highlight)
                <div class="bg-[#f4fdf7] border border-emerald-900/10 rounded-2xl p-3 text-center flex flex-col items-center justify-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-twina-gold/20 text-[#052010] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#052010]">{{ $highlight }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- WELCOME NOTE FROM SAFARI CONSULTANT --}}
        @if($proposal->welcome_message)
        <div class="bg-twina-forest text-white rounded-3xl p-6 md:p-8 mb-10 border border-twina-gold/40 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 text-twina-gold">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
            </div>
            <div class="relative z-10">
                <span class="text-xs font-black text-twina-gold uppercase tracking-widest font-serif-title block mb-2">Message from Your Twina Safaris Consultant</span>
                <p class="text-amber-100/90 text-sm md:text-base leading-relaxed italic font-serif-title text-lg md:text-xl">
                    "{{ $proposal->welcome_message }}"
                </p>
            </div>
        </div>
        @endif

        {{-- MULTI-SECTION NAVIGATION (VERTICAL STACKED BUTTONS GOING DOWNWARDS) --}}
        <div class="mb-10 space-y-3">
            <div class="text-[11px] font-black uppercase text-emerald-900/60 tracking-widest px-1 mb-2">
                Proposal Sections — Tap to Open Each Section:
            </div>

            <div class="flex flex-col gap-3">
                {{-- Button 1: Safari Itinerary --}}
                <button @click="activeTab = 'itinerary'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        :class="activeTab === 'itinerary' ? 'bg-[#052010] text-[#D4AF37] border-twina-gold shadow-xl ring-2 ring-twina-gold/30 scale-[1.01]' : 'bg-white text-[#052010] border-emerald-900/10 hover:bg-[#f4fdf7] shadow-sm'"
                        class="w-full p-4 md:p-5 rounded-2xl border-2 text-left transition-all duration-300 flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span :class="activeTab === 'itinerary' ? 'bg-twina-gold text-[#052010]' : 'bg-twina-gold/20 text-[#052010]'"
                              class="w-9 h-9 rounded-xl font-black text-sm flex items-center justify-center shrink-0 transition-colors shadow-sm">
                            1
                        </span>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">Section 1</span>
                            <span class="text-sm md:text-base font-black tracking-tight">1. Safari Itinerary</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="activeTab === 'itinerary'" class="text-[10px] md:text-xs font-black uppercase text-twina-gold bg-twina-gold/15 px-3 py-1 rounded-full border border-twina-gold/30">Active</span>
                        <svg class="w-5 h-5 text-twina-gold transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                {{-- Button 2: Accommodation Summary --}}
                <button @click="activeTab = 'accommodations'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        :class="activeTab === 'accommodations' ? 'bg-[#052010] text-[#D4AF37] border-twina-gold shadow-xl ring-2 ring-twina-gold/30 scale-[1.01]' : 'bg-white text-[#052010] border-emerald-900/10 hover:bg-[#f4fdf7] shadow-sm'"
                        class="w-full p-4 md:p-5 rounded-2xl border-2 text-left transition-all duration-300 flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span :class="activeTab === 'accommodations' ? 'bg-twina-gold text-[#052010]' : 'bg-twina-gold/20 text-[#052010]'"
                              class="w-9 h-9 rounded-xl font-black text-sm flex items-center justify-center shrink-0 transition-colors shadow-sm">
                            2
                        </span>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">Section 2</span>
                            <span class="text-sm md:text-base font-black tracking-tight">2. Accommodation Summary</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="activeTab === 'accommodations'" class="text-[10px] md:text-xs font-black uppercase text-twina-gold bg-twina-gold/15 px-3 py-1 rounded-full border border-twina-gold/30">Active</span>
                        <svg class="w-5 h-5 text-twina-gold transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                {{-- Button 3: Inclusions & Investment --}}
                <button @click="activeTab = 'pricing'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        :class="activeTab === 'pricing' ? 'bg-[#052010] text-[#D4AF37] border-twina-gold shadow-xl ring-2 ring-twina-gold/30 scale-[1.01]' : 'bg-white text-[#052010] border-emerald-900/10 hover:bg-[#f4fdf7] shadow-sm'"
                        class="w-full p-4 md:p-5 rounded-2xl border-2 text-left transition-all duration-300 flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span :class="activeTab === 'pricing' ? 'bg-twina-gold text-[#052010]' : 'bg-twina-gold/20 text-[#052010]'"
                              class="w-9 h-9 rounded-xl font-black text-sm flex items-center justify-center shrink-0 transition-colors shadow-sm">
                            3
                        </span>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">Section 3</span>
                            <span class="text-sm md:text-base font-black tracking-tight">3. Inclusions & Investment</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="activeTab === 'pricing'" class="text-[10px] md:text-xs font-black uppercase text-twina-gold bg-twina-gold/15 px-3 py-1 rounded-full border border-twina-gold/30">Active</span>
                        <svg class="w-5 h-5 text-twina-gold transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                {{-- Button 4: Payment & Terms --}}
                <button @click="activeTab = 'terms'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        :class="activeTab === 'terms' ? 'bg-[#052010] text-[#D4AF37] border-twina-gold shadow-xl ring-2 ring-twina-gold/30 scale-[1.01]' : 'bg-white text-[#052010] border-emerald-900/10 hover:bg-[#f4fdf7] shadow-sm'"
                        class="w-full p-4 md:p-5 rounded-2xl border-2 text-left transition-all duration-300 flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span :class="activeTab === 'terms' ? 'bg-twina-gold text-[#052010]' : 'bg-twina-gold/20 text-[#052010]'"
                              class="w-9 h-9 rounded-xl font-black text-sm flex items-center justify-center shrink-0 transition-colors shadow-sm">
                            4
                        </span>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">Section 4</span>
                            <span class="text-sm md:text-base font-black tracking-tight">4. Payment & Terms</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="activeTab === 'terms'" class="text-[10px] md:text-xs font-black uppercase text-twina-gold bg-twina-gold/15 px-3 py-1 rounded-full border border-twina-gold/30">Active</span>
                        <svg class="w-5 h-5 text-twina-gold transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            </div>
        </div>

        {{-- TAB 1: DAY-BY-DAY ITINERARY --}}
        <div x-show="activeTab === 'itinerary'" class="space-y-12">
            @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
                <div class="space-y-12">
                    @foreach($proposal->itinerary as $day)
                    <div class="bg-white rounded-3xl p-6 md:p-10 border border-emerald-900/10 shadow-sm hover:shadow-md transition-shadow">
                        {{-- Day Header --}}
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6 pb-4 border-b border-emerald-900/10">
                            <div>
                                <span class="text-xs font-black text-twina-gold uppercase tracking-widest block mb-0.5">
                                    DAY {{ sprintf('%02d', $day['day'] ?? $loop->iteration) }}
                                </span>
                                <h3 class="text-2xl md:text-3xl font-black font-serif-title text-[#052010]">
                                    {{ $day['title'] ?? '' }}
                                </h3>
                            </div>
                            @if(!empty($day['destination']))
                                <span class="px-4 py-1.5 bg-[#f4fdf7] text-[#052010] border border-emerald-900/20 rounded-full text-xs font-black uppercase tracking-wider self-start md:self-auto">
                                    {{ $day['destination'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Large Day Safari Cover Image --}}
                        @if(!empty($day['cover_image']))
                        <div class="mb-8 rounded-3xl overflow-hidden max-h-[440px] bg-[#052010] shadow-md relative group cursor-pointer border border-twina-gold/20"
                             @click="lightboxImg = '{{ $day['cover_image'] }}'">
                            <img src="{{ $day['cover_image'] }}" alt="{{ $day['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs uppercase tracking-widest backdrop-blur-xs">
                                Click to Expand Image
                            </div>
                        </div>
                        @endif

                        {{-- Detailed Itinerary Narrative --}}
                        @if(!empty($day['description']))
                        <div class="prose max-w-none text-emerald-950 text-sm md:text-base leading-relaxed mb-8 font-normal whitespace-pre-line">
                            {{ $day['description'] }}
                        </div>
                        @endif

                        {{-- Day Meta Info Cards (Activities, Accommodation, Meals) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 bg-[#f4fdf7] rounded-2xl border border-emerald-900/10 mb-6">
                            @if(!empty($day['activities']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Activities</div>
                                <div class="text-xs font-bold text-[#052010] mt-1">{{ $day['activities'] }}</div>
                            </div>
                            @endif

                            @if(!empty($day['accommodation_property']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Lodge / Camp</div>
                                <div class="text-xs font-bold text-[#052010] mt-1">{{ $day['accommodation_property'] }} @if(!empty($day['room_type'])) ({{ $day['room_type'] }}) @endif</div>
                            </div>
                            @endif

                            @if(!empty($day['meals']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider">Meals Included</div>
                                <div class="text-xs font-bold text-[#052010] mt-1">{{ $day['meals'] }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Additional Day Gallery Images --}}
                        @if(!empty($day['gallery_images']) && is_array($day['gallery_images']) && count($day['gallery_images']) > 0)
                        <div>
                            <div class="text-[10px] font-black uppercase text-emerald-900/60 tracking-wider mb-2">Day {{ $day['day'] ?? $loop->iteration }} Photo Gallery</div>
                            <div class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-2">
                                @foreach($day['gallery_images'] as $img)
                                @php $imgUrl = is_array($img) ? ($img['url'] ?? '') : $img; @endphp
                                @if(!empty($imgUrl))
                                <div class="w-28 h-20 rounded-2xl overflow-hidden shrink-0 border border-emerald-900/10 cursor-pointer hover:opacity-90 transition-opacity" @click="lightboxImg = '{{ $imgUrl }}'">
                                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Bottom Step Switcher Button --}}
            <div class="pt-6 border-t border-emerald-900/10 flex justify-end">
                <button @click="activeTab = 'accommodations'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="px-6 py-3.5 bg-[#052010] text-[#D4AF37] border border-twina-gold/40 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center gap-3 shadow-lg hover:bg-[#08331a] transition-all">
                    <span>Next Section: 2. Accommodations Summary</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>

        {{-- TAB 2: ACCOMMODATIONS --}}
        <div x-show="activeTab === 'accommodations'" x-cloak class="space-y-8">
            @if(is_array($proposal->accommodations) && count($proposal->accommodations) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($proposal->accommodations as $acc)
                    <div class="bg-white rounded-3xl p-6 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-3 py-1 bg-[#f4fdf7] text-[#052010] border border-emerald-900/20 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    {{ $acc['category'] ?? 'Luxury Safari Comfort' }}
                                </span>
                                <span class="text-xs font-bold text-emerald-900/60">{{ $acc['location'] ?? '' }}</span>
                            </div>
                            <h4 class="text-xl font-black font-serif-title text-[#052010]">{{ $acc['property_name'] ?? 'Safari Lodge' }}</h4>
                            <p class="text-xs text-emerald-950 mt-1 font-medium">{{ $acc['room_type'] ?? '' }} &bull; {{ $acc['meal_plan'] ?? 'Full Board' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 text-center text-emerald-900/60 font-bold text-xs">
                    Accommodations feature handpicked safari lodges and authentic luxury tented camps as detailed in each day's itinerary.
                </div>
            @endif

            {{-- Bottom Step Switcher Buttons --}}
            <div class="pt-6 border-t border-emerald-900/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button @click="activeTab = 'itinerary'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="w-full sm:w-auto px-6 py-3.5 bg-white text-[#052010] border border-emerald-900/20 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-[#f4fdf7] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>← Previous: 1. Safari Itinerary</span>
                </button>

                <button @click="activeTab = 'pricing'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="w-full sm:w-auto px-6 py-3.5 bg-[#052010] text-[#D4AF37] border border-twina-gold/40 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-3 shadow-lg hover:bg-[#08331a] transition-all">
                    <span>Next Section: 3. Pricing & Inclusions</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>

        {{-- TAB 3: PRICING & INCLUSIONS --}}
        <div x-show="activeTab === 'pricing'" x-cloak class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Inclusions --}}
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-emerald-900/10 shadow-sm">
                    <h3 class="text-lg font-black font-serif-title text-[#052010] uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1a9b50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        What Is Included in Your Safari
                    </h3>

                    <ul class="space-y-3">
                        @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                            @foreach($proposal->inclusions as $inc)
                            <li class="flex items-start gap-3 text-xs md:text-sm font-medium text-[#052010]">
                                <svg class="w-5 h-5 text-[#1a9b50] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $inc }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                {{-- Exclusions --}}
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-emerald-900/10 shadow-sm">
                    <h3 class="text-lg font-black font-serif-title text-rose-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        What Is Excluded
                    </h3>

                    <ul class="space-y-3">
                        @if(is_array($proposal->exclusions) && count($proposal->exclusions) > 0)
                            @foreach($proposal->exclusions as $exc)
                            <li class="flex items-start gap-3 text-xs md:text-sm font-medium text-slate-700">
                                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>{{ $exc }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Optional Extras --}}
            @if(is_array($proposal->optional_extras) && count($proposal->optional_extras) > 0)
            <div class="bg-[#f4fdf7] border border-emerald-900/10 p-6 md:p-8 rounded-3xl">
                <h3 class="text-lg font-black font-serif-title text-[#052010] uppercase tracking-wider mb-2">Optional Experiences & Add-ons</h3>
                <p class="text-xs text-emerald-950 mb-6 font-medium">You can request to add any of these optional activities to your safari package.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($proposal->optional_extras as $extra)
                    <div class="bg-white p-4 rounded-2xl border border-emerald-900/10 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black text-[#052010]">{{ $extra['name'] ?? '' }}</div>
                            <div class="text-[11px] text-emerald-900/60">{{ $extra['description'] ?? '' }}</div>
                        </div>
                        <div class="text-xs font-black text-twina-gold shrink-0 ml-3">
                            {{ $proposal->currency_symbol }}{{ number_format($extra['price'] ?? 0, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- PRICING & INVESTMENT BREAKDOWN CARD --}}
            <div class="bg-twina-forest text-white p-6 md:p-10 rounded-3xl shadow-2xl border border-twina-gold/40">
                <div class="text-xs font-black uppercase text-twina-gold tracking-widest mb-1">YOUR SAFARI INVESTMENT</div>
                <h3 class="text-2xl font-black font-serif-title text-white mb-8">Personalized Safari Price Summary</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-8 border-b border-twina-gold/20 text-xs font-medium">
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-amber-100/70">Adult Guests:</span><span class="font-bold text-white">{{ $proposal->adults }} Adults @if($proposal->adult_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->adult_price, 2) }} / adult) @endif</span></div>
                        @if($proposal->children > 0)
                            <div class="flex justify-between"><span class="text-amber-100/70">Child Guests:</span><span class="font-bold text-white">{{ $proposal->children }} Children @if($proposal->child_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->child_price, 2) }} / child) @endif</span></div>
                        @endif
                        @if($proposal->subtotal_price && $proposal->discount_amount > 0)
                            <div class="flex justify-between"><span class="text-amber-100/70">Standard Price:</span><span class="font-bold line-through text-amber-200/50">{{ $proposal->currency_symbol }}{{ number_format($proposal->subtotal_price, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-emerald-400 font-bold">Special Savings:</span><span class="font-bold text-emerald-400">-{{ $proposal->currency_symbol }}{{ number_format($proposal->discount_amount, 2) }}</span></div>
                        @endif
                    </div>

                    <div class="text-left md:text-right space-y-2">
                        <div class="text-[10px] uppercase font-black text-twina-gold tracking-widest">FINAL INVESTMENT TOTAL</div>
                        <div class="text-4xl md:text-5xl font-black text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                    </div>
                </div>

                {{-- Payment Schedule Breakdown --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-8 text-xs">
                    <div class="bg-[#0b381c] p-5 rounded-2xl border border-twina-gold/30">
                        <div class="text-[10px] uppercase font-black text-twina-gold tracking-wider">Deposit Required ({{ $proposal->deposit_percentage ?? 30 }}%)</div>
                        <div class="text-2xl font-black text-white mt-1">{{ $proposal->formatted_deposit_required }}</div>
                        <div class="text-[10px] text-amber-100/70 mt-1">Due to confirm booking</div>
                    </div>

                    <div class="bg-[#0b381c] p-5 rounded-2xl border border-twina-gold/30">
                        <div class="text-[10px] uppercase font-black text-twina-gold tracking-wider">Remaining Balance</div>
                        <div class="text-2xl font-black text-white mt-1">{{ $proposal->formatted_balance_amount }}</div>
                        <div class="text-[10px] text-amber-100/70 mt-1">
                            Due: {{ $proposal->balance_due_date ? $proposal->balance_due_date->format('M d, Y') : '30 days prior to travel' }}
                        </div>
                    </div>

                    <div class="bg-[#0b381c] p-5 rounded-2xl border border-twina-gold/30">
                        <div class="text-[10px] uppercase font-black text-twina-gold tracking-wider">Quotation Validity</div>
                        <div class="text-sm font-black text-amber-300 mt-2">
                            Valid Until {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
                        </div>
                        <div class="text-[10px] text-amber-100/70 mt-1">Subject to lodge availability</div>
                    </div>
                </div>
            </div>

            {{-- Bottom Step Switcher Buttons --}}
            <div class="pt-6 border-t border-emerald-900/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button @click="activeTab = 'accommodations'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="w-full sm:w-auto px-6 py-3.5 bg-white text-[#052010] border border-emerald-900/20 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-[#f4fdf7] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>← Previous: 2. Accommodations</span>
                </button>

                <button @click="activeTab = 'terms'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="w-full sm:w-auto px-6 py-3.5 bg-[#052010] text-[#D4AF37] border border-twina-gold/40 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-3 shadow-lg hover:bg-[#08331a] transition-all">
                    <span>Next Section: 4. Payment & Terms</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>

        {{-- TAB 4: PAYMENT & TERMS --}}
        <div x-show="activeTab === 'terms'" x-cloak class="space-y-8">
            <div class="bg-white p-6 md:p-10 rounded-3xl border border-emerald-900/10 shadow-sm space-y-8">
                <div>
                    <h3 class="text-lg font-black font-serif-title text-[#052010] uppercase tracking-wider mb-3">Payment Terms & Methods</h3>
                    <p class="text-sm text-emerald-950 leading-relaxed whitespace-pre-line font-medium mb-4">
                        {{ $proposal->payment_terms ?: "A deposit of 30% is required upon booking confirmation. The remaining balance is payable 30 days prior to your arrival date. We accept Bank Wire Transfers and major Credit/Debit Cards." }}
                    </p>

                    @if($proposal->payment_methods)
                        <div class="p-4 bg-[#f4fdf7] rounded-2xl border border-emerald-900/10 text-xs font-medium text-[#052010]">
                            <strong>Accepted Payment Methods:</strong> {{ $proposal->payment_methods }}
                        </div>
                    @endif

                    @if($proposal->payment_instructions)
                        <div class="mt-3 p-4 bg-twina-forest/5 rounded-2xl border border-twina-gold/30 text-xs font-medium text-[#052010]">
                            <strong>Payment Instructions:</strong> {{ $proposal->payment_instructions }}
                        </div>
                    @endif
                </div>

                <div class="border-t border-emerald-900/10 pt-6">
                    <h3 class="text-lg font-black font-serif-title text-[#052010] uppercase tracking-wider mb-3">Cancellation & Refund Policy</h3>
                    <p class="text-sm text-emerald-950 leading-relaxed whitespace-pre-line font-medium">
                        {{ $proposal->cancellation_policy ?: "Cancellations made 60+ days before travel are subject to standard processing and lodge cancellation fees." }}
                    </p>
                    @if($proposal->refund_policy)
                        <p class="text-xs text-emerald-900/70 mt-2 font-medium">
                            <strong>Refund terms:</strong> {{ $proposal->refund_policy }}
                        </p>
                    @endif
                </div>

                <div class="border-t border-emerald-900/10 pt-6">
                    <h3 class="text-lg font-black font-serif-title text-[#052010] uppercase tracking-wider mb-3">Price Validity Notice</h3>
                    <p class="text-xs text-emerald-900/70 italic font-medium">
                        "This quotation is valid until {{ $proposal->valid_until ? $proposal->valid_until->format('d F Y') : now()->addDays(30)->format('d F Y') }} and is subject to availability of safari accommodations and park permits."
                    </p>
                </div>
            </div>

            {{-- Bottom Step Switcher Buttons --}}
            <div class="pt-6 border-t border-emerald-900/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button @click="activeTab = 'pricing'; document.getElementById('proposal-content').scrollIntoView({behavior: 'smooth'})"
                        class="w-full sm:w-auto px-6 py-3.5 bg-white text-[#052010] border border-emerald-900/20 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-[#f4fdf7] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>← Previous: 3. Pricing & Inclusions</span>
                </button>

                <button @click="acceptModal = true"
                        class="w-full sm:w-auto px-8 py-3.5 btn-gold rounded-2xl text-xs uppercase tracking-wider shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span>Accept Proposal Now</span>
                </button>
            </div>
        </div>
    </main>

    {{-- BOTTOM STICKY ACTION BAR --}}
    <div class="fixed bottom-0 inset-x-0 bg-twina-forest/95 backdrop-blur-md border-t border-twina-gold/40 py-4 px-4 sm:px-6 z-40 text-white shadow-2xl">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-center sm:text-left">
                <div class="text-[10px] uppercase font-black text-twina-gold tracking-widest">YOUR SAFARI INVESTMENT</div>
                <div class="text-2xl font-black font-serif-title text-white">{{ $proposal->formatted_total_price }}</div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank"
                   class="px-4 py-3 bg-emerald-700/30 hover:bg-emerald-700/50 text-emerald-300 border border-emerald-500/40 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Contact Twina Safaris</span>
                </a>

                <button @click="changesModal = true"
                        class="flex-1 sm:flex-none px-5 py-3 btn-forest rounded-xl text-xs uppercase tracking-wider">
                    Request Changes
                </button>

                @if($proposal->status === \App\Models\Proposal::STATUS_ACCEPTED)
                    <div class="flex-1 sm:flex-none px-6 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <span>Proposal Accepted</span>
                    </div>
                @else
                    <button @click="acceptModal = true"
                            class="flex-1 sm:flex-none px-6 py-3 btn-gold rounded-xl text-xs uppercase tracking-wider shadow-lg">
                        Accept Proposal
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- LIGHTBOX MODAL --}}
    <div x-show="lightboxImg" x-cloak class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @click="lightboxImg = null">
        <img :src="lightboxImg" class="max-w-full max-h-full rounded-2xl shadow-2xl border border-twina-gold/40">
    </div>

    {{-- ACCEPT PROPOSAL MODAL --}}
    <div x-show="acceptModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="acceptModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 text-[#052010] shadow-2xl border border-emerald-900/10 relative">
            <button @click="acceptModal = false" class="absolute top-5 right-5 text-emerald-900/40 hover:text-[#052010]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-2xl font-black font-serif-title text-[#052010] mb-2">Accept Safari Proposal</h3>
            <p class="text-xs text-emerald-900/70 mb-6">Confirm your acceptance for <strong>{{ $proposal->title }}</strong> ({{ $proposal->formatted_total_price }}).</p>

            <form action="{{ route('proposal.accept', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-[#052010] mb-1">Your Full Name (Electronic Signature) *</label>
                    <input type="text" name="signature_name" required value="{{ $proposal->client_name }}"
                           class="w-full bg-[#f4fdf7] border border-emerald-900/20 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-twina-gold">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-[#052010] mb-1">Additional Notes (Optional)</label>
                    <textarea name="client_notes" rows="3"
                              class="w-full bg-[#f4fdf7] border border-emerald-900/20 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-twina-gold"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="acceptModal = false" class="px-5 py-2.5 text-xs font-bold uppercase text-emerald-900/60">Cancel</button>
                    <button type="submit" class="px-6 py-3 btn-gold text-xs uppercase rounded-xl shadow">
                        Confirm & Accept Safari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- REQUEST CHANGES MODAL --}}
    <div x-show="changesModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="changesModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 text-[#052010] shadow-2xl border border-emerald-900/10 relative">
            <button @click="changesModal = false" class="absolute top-5 right-5 text-emerald-900/40 hover:text-[#052010]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-2xl font-black font-serif-title text-[#052010] mb-2">Request Changes</h3>
            <p class="text-xs text-emerald-900/70 mb-6">Let us know what adjustments or customization you'd like on this safari itinerary.</p>

            <form action="{{ route('proposal.changes', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-[#052010] mb-1">What would you like us to change? *</label>
                    <textarea name="feedback" rows="5" required
                              class="w-full bg-[#f4fdf7] border border-emerald-900/20 rounded-xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-twina-gold"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="changesModal = false" class="px-5 py-2.5 text-xs font-bold uppercase text-emerald-900/60">Cancel</button>
                    <button type="submit" class="px-6 py-3 btn-forest rounded-xl shadow text-xs uppercase">
                        Submit Change Request
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
