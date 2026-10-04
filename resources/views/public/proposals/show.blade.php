<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Safari Proposal - {{ $proposal->title }} | Twina Safaris</title>

    {{-- Favicon --}}
    @if(\App\Models\Setting::get('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Tailwind CSS & Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .font-serif-title { font-family: 'Playfair Display', Georgia, serif; }
        .gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen pb-24" x-data="{ acceptModal: false, changesModal: false, activeTab: 'itinerary', lightboxImg: null }">

    {{-- Top Brand Bar --}}
    <header class="bg-slate-950 border-b border-amber-500/20 text-white sticky top-0 z-40 backdrop-blur-md bg-opacity-95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-xl font-black tracking-wider font-serif-title text-amber-400">TWINA SAFARIS</span>
                </a>
                <span class="hidden sm:inline-block text-xs font-bold text-amber-500/60 uppercase tracking-widest pl-3 border-l border-amber-500/20">Client Proposal &bull; {{ $proposal->full_reference }}</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank" class="px-4 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span class="hidden sm:inline">Contact</span> Twina
                </a>
                <a href="{{ route('proposal.pdf', $proposal->token) }}" class="px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="hidden sm:inline">Download</span> PDF
                </a>
            </div>
        </div>
    </header>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="bg-emerald-50 border-2 border-emerald-500 text-emerald-900 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-xs font-bold">{!! session('success') !!}</div>
        </div>
    </div>
    @endif

    {{-- Hero Banner --}}
    <section class="relative bg-slate-900 text-white overflow-hidden py-12 md:py-20 border-b border-amber-500/20">
        <div class="absolute inset-0 z-0 opacity-30 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1600&q=80')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/20 border border-amber-500/40 rounded-full text-amber-300 text-xs font-bold uppercase tracking-wider mb-4">
                        <span>Ref: {{ $proposal->full_reference }}</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black tracking-tight font-serif-title text-white max-w-3xl leading-tight">
                        {{ $proposal->title }}
                    </h1>

                    @if($proposal->subtitle)
                        <p class="text-amber-400 font-semibold text-sm md:text-base mt-2">{{ $proposal->subtitle }}</p>
                    @endif

                    <p class="text-slate-300 font-medium text-xs md:text-sm mt-3 flex items-center gap-2">
                        Prepared specially for <strong class="text-white">{{ $proposal->client_name }}</strong>
                    </p>
                </div>

                {{-- Status & Travel Quick Badge --}}
                <div class="bg-slate-900/90 border border-amber-500/30 backdrop-blur-md rounded-2xl p-5 text-right flex flex-col items-start md:items-end gap-2 shrink-0">
                    <div class="text-[10px] uppercase font-black text-amber-400 tracking-widest">YOUR SAFARI INVESTMENT</div>
                    <div class="text-3xl font-black text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                    <div class="text-xs text-slate-300">
                        {{ $proposal->adults }} Adults @if($proposal->children > 0), {{ $proposal->children }} Children @endif
                        @if($proposal->adult_price) &bull; {{ $proposal->currency_symbol }}{{ number_format($proposal->adult_price, 2) }} per person @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Proposal Details Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        {{-- Route Chain Summary Banner --}}
        <div class="bg-slate-900 text-white rounded-2xl p-4 md:p-5 mb-8 border border-amber-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-amber-400 tracking-wider">Your Safari Route</div>
                    <div class="text-xs md:text-sm font-black text-white mt-0.5">{{ $proposal->route_chain }}</div>
                </div>
            </div>

            <div class="text-xs text-amber-300/90 font-bold bg-amber-500/10 border border-amber-500/30 px-3.5 py-1.5 rounded-xl self-start sm:self-auto">
                Quotation Valid Until: {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
            </div>
        </div>

        {{-- Safari Highlights Visual Summary --}}
        @if(is_array($proposal->highlights) && count($proposal->highlights) > 0)
        <div class="mb-10 bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 font-serif-title">Safari Highlights & Key Experiences</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                @foreach($proposal->highlights as $highlight)
                <div class="bg-amber-50/50 border border-amber-200/60 rounded-2xl p-3 text-center flex flex-col items-center justify-center gap-1.5">
                    <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs font-black text-slate-800">{{ $highlight }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Travel Dates</div>
                    <div class="text-xs font-black text-slate-900 mt-0.5">
                        @if($proposal->start_date)
                            {{ $proposal->start_date->format('M d, Y') }}
                            @if($proposal->end_date) - {{ $proposal->end_date->format('M d, Y') }} @endif
                        @else
                            Flexible Dates
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Duration</div>
                    <div class="text-xs font-black text-slate-900 mt-0.5">{{ $proposal->duration_days }} Days / {{ $proposal->duration_nights }} Nights</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Guests</div>
                    <div class="text-xs font-black text-slate-900 mt-0.5">{{ $proposal->adults }} Adult(s) @if($proposal->children > 0), {{ $proposal->children }} Child(ren) @endif</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Safari Level</div>
                    <div class="text-xs font-black uppercase mt-0.5 text-amber-600">
                        {{ $proposal->accommodation_level ?: 'Comfort' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Welcome Note --}}
        @if($proposal->welcome_message)
        <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-6 md:p-8 mb-10">
            <h3 class="text-xs font-black text-amber-800 uppercase tracking-widest mb-2 font-serif-title">Message from your Safari Consultant</h3>
            <p class="text-slate-700 text-sm md:text-base leading-relaxed italic">
                "{{ $proposal->welcome_message }}"
            </p>
        </div>
        @endif

        {{-- Navigation Tabs --}}
        <div class="border-b border-slate-200 mb-8 flex gap-6 overflow-x-auto no-scrollbar">
            <button @click="activeTab = 'itinerary'"
                    :class="activeTab === 'itinerary' ? 'border-amber-500 text-amber-600 font-black' : 'border-transparent text-slate-500 font-bold hover:text-slate-900'"
                    class="pb-4 text-sm uppercase tracking-wider border-b-2 whitespace-nowrap transition-all">
                1. Safari Itinerary
            </button>
            <button @click="activeTab = 'accommodations'"
                    :class="activeTab === 'accommodations' ? 'border-amber-500 text-amber-600 font-black' : 'border-transparent text-slate-500 font-bold hover:text-slate-900'"
                    class="pb-4 text-sm uppercase tracking-wider border-b-2 whitespace-nowrap transition-all">
                2. Accommodation Summary
            </button>
            <button @click="activeTab = 'pricing'"
                    :class="activeTab === 'pricing' ? 'border-amber-500 text-amber-600 font-black' : 'border-transparent text-slate-500 font-bold hover:text-slate-900'"
                    class="pb-4 text-sm uppercase tracking-wider border-b-2 whitespace-nowrap transition-all">
                3. Inclusions & Investment
            </button>
            <button @click="activeTab = 'terms'"
                    :class="activeTab === 'terms' ? 'border-amber-500 text-amber-600 font-black' : 'border-transparent text-slate-500 font-bold hover:text-slate-900'"
                    class="pb-4 text-sm uppercase tracking-wider border-b-2 whitespace-nowrap transition-all">
                4. Payment & Terms
            </button>
        </div>

        {{-- TAB 1: ITINERARY WITH DAY-SPECIFIC IMAGES --}}
        <div x-show="activeTab === 'itinerary'" class="space-y-12">
            @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
                <div class="space-y-12">
                    @foreach($proposal->itinerary as $day)
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-4 pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-black text-amber-600 uppercase tracking-widest">DAY {{ $day['day'] ?? $loop->iteration }}</span>
                                <h3 class="text-xl md:text-2xl font-black font-serif-title text-slate-900 mt-0.5">
                                    {{ $day['title'] ?? '' }}
                                </h3>
                            </div>
                            @if(!empty($day['destination']))
                                <span class="px-3.5 py-1 bg-amber-50 text-amber-900 border border-amber-200 rounded-full text-xs font-black uppercase tracking-wider self-start md:self-auto">
                                    {{ $day['destination'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Day Cover Image --}}
                        @if(!empty($day['cover_image']))
                        <div class="mb-6 rounded-2xl overflow-hidden max-h-96 bg-slate-100 shadow-sm relative group cursor-pointer" @click="lightboxImg = '{{ $day['cover_image'] }}'">
                            <img src="{{ $day['cover_image'] }}" alt="{{ $day['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs uppercase tracking-wider">Click to view full image</div>
                        </div>
                        @endif

                        {{-- Description --}}
                        @if(!empty($day['description']))
                        <p class="text-slate-700 text-sm md:text-base leading-relaxed mb-6 font-medium whitespace-pre-line">
                            {{ $day['description'] }}
                        </p>
                        @endif

                        {{-- Activities, Accommodation, Meals Badges --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/60 mb-6">
                            @if(!empty($day['activities']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Activities</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $day['activities'] }}</div>
                            </div>
                            @endif

                            @if(!empty($day['accommodation_property']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Accommodation</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $day['accommodation_property'] }} @if(!empty($day['room_type'])) ({{ $day['room_type'] }}) @endif</div>
                            </div>
                            @endif

                            @if(!empty($day['meals']))
                            <div>
                                <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Meals Included</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $day['meals'] }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Small Additional Gallery --}}
                        @if(!empty($day['gallery_images']) && is_array($day['gallery_images']) && count($day['gallery_images']) > 0)
                        <div>
                            <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Additional Gallery for Day {{ $day['day'] ?? $loop->iteration }}</div>
                            <div class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-2">
                                @foreach($day['gallery_images'] as $img)
                                @php $imgUrl = is_array($img) ? ($img['url'] ?? '') : $img; @endphp
                                @if(!empty($imgUrl))
                                <div class="w-24 h-20 rounded-xl overflow-hidden shrink-0 border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity" @click="lightboxImg = '{{ $imgUrl }}'">
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
        </div>

        {{-- TAB 2: ACCOMMODATION SUMMARY --}}
        <div x-show="activeTab === 'accommodations'" x-cloak class="space-y-8">
            @if(is_array($proposal->accommodations) && count($proposal->accommodations) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($proposal->accommodations as $acc)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 bg-amber-50 text-amber-900 border border-amber-200 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    {{ $acc['category'] ?? 'Comfort' }}
                                </span>
                                <span class="text-xs font-bold text-slate-400">{{ $acc['location'] ?? '' }}</span>
                            </div>
                            <h4 class="text-lg font-black font-serif-title text-slate-900">{{ $acc['property_name'] ?? 'Safari Lodge' }}</h4>
                            <p class="text-xs text-slate-600 mt-1 font-medium">{{ $acc['room_type'] ?? '' }} &bull; {{ $acc['meal_plan'] ?? 'Full Board' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 text-center text-slate-500 font-bold text-xs">
                    Accommodations feature handpicked lodges and luxury safari camps as specified in the itinerary.
                </div>
            @endif
        </div>

        {{-- TAB 3: INCLUSIONS & PRICING --}}
        <div x-show="activeTab === 'pricing'" x-cloak class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Inclusions --}}
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                    <h3 class="text-base font-black font-serif-title text-emerald-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        What Is Included
                    </h3>

                    <ul class="space-y-3">
                        @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                            @foreach($proposal->inclusions as $inc)
                            <li class="flex items-start gap-3 text-xs md:text-sm font-medium text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $inc }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                {{-- Exclusions --}}
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                    <h3 class="text-base font-black font-serif-title text-rose-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        What Is Excluded
                    </h3>

                    <ul class="space-y-3">
                        @if(is_array($proposal->exclusions) && count($proposal->exclusions) > 0)
                            @foreach($proposal->exclusions as $exc)
                            <li class="flex items-start gap-3 text-xs md:text-sm font-medium text-slate-700">
                                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>{{ $exc }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Optional Extras Section --}}
            @if(is_array($proposal->optional_extras) && count($proposal->optional_extras) > 0)
            <div class="bg-amber-50/60 border border-amber-200 p-6 md:p-8 rounded-3xl">
                <h3 class="text-base font-black font-serif-title text-amber-900 uppercase tracking-wider mb-2">Optional Experiences — Not Included</h3>
                <p class="text-xs text-slate-600 mb-6 font-medium">You can choose to add any of these optional activities to your safari package.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($proposal->optional_extras as $extra)
                    <div class="bg-white p-4 rounded-2xl border border-amber-200/80 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black text-slate-900">{{ $extra['name'] ?? '' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $extra['description'] ?? '' }}</div>
                        </div>
                        <div class="text-xs font-black text-amber-600 shrink-0 ml-3">
                            {{ $proposal->currency_symbol }}{{ number_format($extra['price'] ?? 0, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Client-Facing Pricing Breakdown Card --}}
            <div class="bg-slate-900 text-white p-6 md:p-8 rounded-3xl shadow-xl border border-amber-500/30">
                <h3 class="text-lg font-black font-serif-title text-amber-400 mb-6">Your Safari Investment Summary</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-800 text-xs font-medium">
                    <div class="space-y-3">
                        <div class="flex justify-between"><span class="text-slate-400">Adult Guests:</span><span class="font-bold text-white">{{ $proposal->adults }} Adults @if($proposal->adult_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->adult_price, 2) }} / person) @endif</span></div>
                        @if($proposal->children > 0)
                            <div class="flex justify-between"><span class="text-slate-400">Child Guests:</span><span class="font-bold text-white">{{ $proposal->children }} Children @if($proposal->child_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->child_price, 2) }} / child) @endif</span></div>
                        @endif
                        @if($proposal->subtotal_price && $proposal->discount_amount > 0)
                            <div class="flex justify-between"><span class="text-slate-400">Original Subtotal:</span><span class="font-bold line-through text-slate-400">{{ $proposal->currency_symbol }}{{ number_format($proposal->subtotal_price, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-emerald-400">Special Discount:</span><span class="font-bold text-emerald-400">-{{ $proposal->currency_symbol }}{{ number_format($proposal->discount_amount, 2) }}</span></div>
                        @endif
                    </div>

                    <div class="text-left md:text-right space-y-2">
                        <div class="text-[10px] uppercase font-black text-amber-400 tracking-widest">Final Total Selling Price</div>
                        <div class="text-3xl md:text-4xl font-black text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                    </div>
                </div>

                {{-- Payment Schedule Breakdown --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 text-xs">
                    <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                        <div class="text-[10px] uppercase font-black text-amber-400 tracking-wider">Deposit ({{ $proposal->deposit_percentage ?? 30 }}%) - Due Now</div>
                        <div class="text-xl font-black text-white mt-1">{{ $proposal->formatted_deposit_required }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Required to confirm booking</div>
                    </div>

                    <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                        <div class="text-[10px] uppercase font-black text-amber-400 tracking-wider">Remaining Balance</div>
                        <div class="text-xl font-black text-white mt-1">{{ $proposal->formatted_balance_amount }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            Due date: {{ $proposal->balance_due_date ? $proposal->balance_due_date->format('M d, Y') : '30 days prior to travel' }}
                        </div>
                    </div>

                    <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                        <div class="text-[10px] uppercase font-black text-amber-400 tracking-wider">Quotation Validity</div>
                        <div class="text-sm font-black text-amber-300 mt-1">
                            Valid Until {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Subject to lodge availability</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 4: PAYMENT & TERMS --}}
        <div x-show="activeTab === 'terms'" x-cloak class="space-y-8">
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="text-base font-black font-serif-title text-slate-900 uppercase tracking-wider mb-3">Payment Terms & Methods</h3>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line font-medium mb-4">
                        {{ $proposal->payment_terms ?: "A deposit of 30% is required to confirm your booking. The remaining balance is payable 30 days prior to your arrival date. We accept Bank Wire Transfers and major credit cards." }}
                    </p>

                    @if($proposal->payment_methods)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs font-medium text-slate-700">
                            <strong>Accepted Payment Methods:</strong> {{ $proposal->payment_methods }}
                        </div>
                    @endif

                    @if($proposal->payment_instructions)
                        <div class="mt-3 p-4 bg-amber-50/60 rounded-2xl border border-amber-200 text-xs font-medium text-amber-900">
                            <strong>Payment Instructions:</strong> {{ $proposal->payment_instructions }}
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-base font-black font-serif-title text-slate-900 uppercase tracking-wider mb-3">Cancellation & Refund Policy</h3>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line font-medium">
                        {{ $proposal->cancellation_policy ?: "Cancellations made 60+ days before travel are subject to standard processing fees." }}
                    </p>
                    @if($proposal->refund_policy)
                        <p class="text-xs text-slate-500 mt-2 font-medium">
                            <strong>Refund terms:</strong> {{ $proposal->refund_policy }}
                        </p>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-base font-black font-serif-title text-slate-900 uppercase tracking-wider mb-3">Price Validity Notice</h3>
                    <p class="text-xs text-slate-600 italic font-medium">
                        "This quotation is valid until {{ $proposal->valid_until ? $proposal->valid_until->format('d F Y') : now()->addDays(30)->format('d F Y') }} and is subject to availability of accommodations and park permits."
                    </p>
                </div>
            </div>
        </div>
    </main>

    {{-- Bottom Action Bar --}}
    <div class="fixed bottom-0 inset-x-0 bg-slate-900/95 backdrop-blur-md border-t border-amber-500/20 py-4 px-4 sm:px-6 z-40 text-white shadow-2xl">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-center sm:text-left">
                <div class="text-[10px] uppercase font-black text-amber-400 tracking-wider">YOUR SAFARI INVESTMENT</div>
                <div class="text-xl font-black font-serif-title">{{ $proposal->formatted_total_price }}</div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank" class="px-4 py-3 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Contact Twina
                </a>

                <button @click="changesModal = true" class="flex-1 sm:flex-none px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-black uppercase tracking-wider border border-slate-700 transition-all">
                    Request Changes
                </button>

                @if($proposal->status === \App\Models\Proposal::STATUS_ACCEPTED)
                    <div class="flex-1 sm:flex-none px-6 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Proposal Accepted
                    </div>
                @else
                    <button @click="acceptModal = true" class="flex-1 sm:flex-none px-6 py-3 gold-gradient hover:opacity-95 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition-all">
                        Accept Proposal
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Image Lightbox Modal --}}
    <div x-show="lightboxImg" x-cloak class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @click="lightboxImg = null">
        <img :src="lightboxImg" class="max-w-full max-h-full rounded-2xl shadow-2xl">
    </div>

    {{-- ACCEPT PROPOSAL MODAL --}}
    <div x-show="acceptModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="acceptModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 text-slate-900 shadow-2xl border border-slate-100 relative">
            <button @click="acceptModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-2xl font-black font-serif-title text-slate-900 mb-2">Accept Safari Proposal</h3>
            <p class="text-xs text-slate-500 mb-6">Confirm your acceptance for <strong>{{ $proposal->title }}</strong> ({{ $proposal->formatted_total_price }}).</p>

            <form action="{{ route('proposal.accept', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Your Full Name (Electronic Signature) *</label>
                    <input type="text" name="signature_name" required value="{{ $proposal->client_name }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Additional Notes (Optional)</label>
                    <textarea name="client_notes" rows="3"
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs focus:outline-none"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="acceptModal = false" class="px-5 py-2.5 text-xs font-bold uppercase text-slate-500">Cancel</button>
                    <button type="submit" class="px-6 py-3 gold-gradient text-white font-black text-xs uppercase rounded-xl shadow">
                        Confirm & Accept Safari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- REQUEST CHANGES MODAL --}}
    <div x-show="changesModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="changesModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 text-slate-900 shadow-2xl border border-slate-100 relative">
            <button @click="changesModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-2xl font-black font-serif-title text-slate-900 mb-2">Request Changes</h3>
            <p class="text-xs text-slate-500 mb-6">Let us know what adjustments or customization you'd like on this safari itinerary.</p>

            <form action="{{ route('proposal.changes', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">What would you like us to change? *</label>
                    <textarea name="feedback" rows="5" required
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl p-4 text-xs font-medium focus:outline-none"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="changesModal = false" class="px-5 py-2.5 text-xs font-bold uppercase text-slate-500">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-black text-xs uppercase rounded-xl shadow">
                        Submit Change Request
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
