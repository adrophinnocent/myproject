@extends('public.layouts.app')

@section('title', 'About Us - Twina Safaris')
@section('meta_description', 'Discover Twina Safaris: Our story, mission, and why we are Tanzania\'s most trusted safari operator.')

@section('content')
{{-- 1. HERO BANNER --}}
<div class="relative h-[70vh] min-h-[500px] flex items-center bg-safari-dark overflow-hidden text-center">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('about_banner') }}"
         alt="Twina Safaris About Us"
         class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-transparent"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <span class="inline-block text-gold-400 text-xs font-black uppercase tracking-[0.5em] mb-6 animate-pulse bg-black/30 backdrop-blur-sm px-6 py-2 rounded-full border border-gold-500/20">
            Tanzania
        </span>
        <h1 class="font-display text-5xl md:text-8xl text-white font-bold leading-tight drop-shadow-2xl">
            Our Passion
        </h1>
        <p class="text-gold-400 text-lg md:text-2xl mt-6 max-w-3xl mx-auto font-bold leading-relaxed drop-shadow-md">
            Sharing the breathtaking beauty, wildlife, and culture of Tanzania with travelers from around the globe.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-[#fcfaf7] to-transparent"></div>
</div>

{{-- 2. THE STORY --}}
<section class="py-24 bg-[#fcfaf7] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div class="space-y-8">
                <div>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark mb-6 leading-tight">
                        Discover the True Spirit <br><span class="italic text-gold-600">of Africa</span>
                    </h2>
                    <div class="w-20 h-1.5 bg-gold-500 rounded-full mb-8"></div>
                </div>
                <div class="space-y-6 text-gray-700 text-lg leading-relaxed font-light">
                    <p>
                        Twina Safaris was founded with a simple mission: to share the breathtaking beauty, wildlife, and culture of Tanzania with travelers from around the world. What started as a passion for adventure and nature has grown into a trusted safari company dedicated to creating unforgettable travel experiences.
                    </p>
                    <p>
                        From the endless plains of the Serengeti to the majestic peak of Mount Kilimanjaro and the pristine beaches of Zanzibar, we believe every journey should be more than just a trip—it should be a life-changing experience. Our team combines local knowledge, professional expertise, and genuine hospitality to ensure that every guest discovers the true spirit of Africa.
                    </p>
                    <p>
                        Over the years, we have helped travelers explore Tanzania's most iconic destinations while connecting them with local communities, wildlife, and natural wonders. Whether it's witnessing the Great Migration, climbing Africa's highest mountain, or enjoying a relaxing beach holiday, we design each adventure with attention to detail and a commitment to excellence.
                    </p>
                </div>
            </div>
            <div class="relative">
                @if($featuredImage = \App\Models\Setting::get('featured_image'))
                    <div class="w-full rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('storage/' . $featuredImage) }}" class="w-full h-auto object-cover" alt="About Twina Safaris">
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kili-1') }}" class="rounded-[2rem] shadow-2xl h-64 w-full object-cover" alt="Safari">
                            <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kili-2') }}" class="rounded-[2rem] shadow-2xl h-80 w-full object-cover" alt="Culture">
                        </div>
                        <div class="space-y-4 pt-12">
                            <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kili-3') }}" class="rounded-[2rem] shadow-2xl h-80 w-full object-cover" alt="Wildlife">
                            <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kili-4') }}" class="rounded-[2rem] shadow-2xl h-64 w-full object-cover" alt="Beach">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- MEET THE FOUNDER --}}
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-24">
            <div class="w-full md:w-1/3 lg:w-1/4">
                <div class="relative group">
                    <div class="absolute -inset-4 bg-gold-500/10 rounded-full blur-2xl group-hover:bg-gold-500/20 transition-all"></div>
                    <div class="relative aspect-square rounded-full overflow-hidden shadow-2xl border-4 border-white ring-8 ring-gold-50">
                        <img src="{{ asset('images/founder.jpg') }}"
                             onerror="this.src='{{ asset('images/logo.png') }}'"
                             class="w-full h-full object-cover"
                             alt="Founder of Twina Safaris">
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/3 lg:w-3/4 space-y-8 text-center md:text-left">
                <div>
                    <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">Meet the Founder</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark mb-2">Twina Safaris Founder</h2>
                    <p class="text-gold-600 text-lg font-bold tracking-wide">Founder & Safari Guide – Twina Safaris</p>
                </div>

                <div class="max-w-2xl">
                    <p class="text-gray-700 text-xl leading-relaxed font-light italic border-l-4 border-gold-500 pl-8 md:pl-10">
                        "Passionate about Tanzanian wildlife, safari experiences, and creating unforgettable journeys for international and local travelers."
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-4">
                    <a href="https://wa.me/255795482197" class="px-8 py-4 bg-safari-dark text-white text-xs font-black uppercase tracking-[0.2em] rounded-full shadow-xl hover:bg-black transition-all">
                        Chat on WhatsApp
                    </a>
                    <a href="mailto:info@twinasafaris.com" class="px-8 py-4 border-2 border-gold-500 text-gold-600 text-xs font-black uppercase tracking-[0.2em] rounded-full hover:bg-gold-500 hover:text-white transition-all">
                        Send an Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. MISSION & VISION --}}
<section class="py-24 bg-safari-dark text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24">
            <div class="bg-white/5 backdrop-blur-md p-12 rounded-[3rem] border border-white/10">
                <h3 class="font-display text-4xl font-bold mb-6 text-gold-400">Our Mission</h3>
                <p class="text-gray-300 text-lg leading-relaxed font-light">
                    Our mission is to create exceptional travel experiences that connect people with the breathtaking landscapes, diverse wildlife, and rich cultural heritage of Tanzania. We are committed to delivering personalized service, professional guidance, and carefully planned adventures that exceed expectations.
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-12 rounded-[3rem] border border-white/10">
                <h3 class="font-display text-4xl font-bold mb-6 text-white">Our Vision</h3>
                <p class="text-gray-300 text-lg leading-relaxed font-light">
                    To become one of Tanzania's most trusted and respected safari companies, recognized internationally for excellence in service, authentic travel experiences, environmental responsibility, and a commitment to showcasing the beauty of Tanzania to the world.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- 4. TOURS & DESTINATIONS --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Column 1: Our Tours We Provide --}}
            <div class="bg-safari-dark rounded-[3rem] p-10 lg:p-14 shadow-2xl relative overflow-hidden group">
                {{-- Decorative background text --}}
                <div class="absolute -right-10 -bottom-10 text-[10rem] font-black text-white/[0.03] select-none pointer-events-none group-hover:text-gold-500/[0.05] transition-colors duration-700">TOURS</div>

                <div class="relative z-10">
                    <h2 class="text-3xl font-display font-bold text-white mb-10 flex items-center gap-4">
                        <span class="w-10 h-1 bg-gold-500 rounded-full"></span>
                        Our Tours We Provide
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                        @foreach([
                            ['name' => 'Safari Tours', 'link' => route('tours.index', ['category' => 1])],
                            ['name' => 'Kilimanjaro Trekking', 'link' => route('tours.index', ['category' => 2])],
                            ['name' => 'Zanzibar Holidays', 'link' => route('tours.index', ['category' => 3])],
                            ['name' => 'Luxury Safaris', 'link' => route('tours.index', ['tour_type' => 'luxury-safaris'])],
                            ['name' => 'Family Safaris', 'link' => route('tours.index', ['tour_type' => 'family-safaris'])],
                            ['name' => 'Honeymoon Safaris', 'link' => route('tours.index', ['tour_type' => 'honeymoon'])],
                            ['name' => 'Day Trips', 'link' => route('tours.index', ['tour_type' => 'day-trips'])],
                            ['name' => 'Group Tours', 'link' => route('tours.index', ['tour_type' => 'group-tours'])]
                        ] as $item)
                        <a href="{{ $item['link'] }}" class="flex items-center gap-4 py-4 px-6 rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-all duration-300 group/link">
                            <div class="w-8 h-8 rounded-full bg-gold-500/10 flex items-center justify-center group-hover/link:bg-gold-500 transition-colors duration-300">
                                <svg class="w-4 h-4 text-gold-500 group-hover/link:text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </div>
                            <span class="text-gray-300 group-hover/link:text-gold-400 font-bold transition-colors">{{ $item['name'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Column 2: Popular Destinations --}}
            <div class="bg-[#fcfaf7] rounded-[3rem] p-10 lg:p-14 border border-gold-500/10 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 text-[10rem] font-black text-safari-dark/[0.02] select-none pointer-events-none group-hover:text-gold-500/[0.03] transition-colors duration-700 uppercase">Africa</div>

                <div class="relative z-10">
                    <h2 class="text-3xl font-display font-bold text-safari-dark mb-10 flex items-center gap-4">
                        <span class="w-10 h-1 bg-safari-dark rounded-full"></span>
                        Popular Destinations
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(\App\Models\Destination::where('is_active', true)->take(6)->get() as $dest)
                        <a href="{{ route('tours.index', ['destination' => $dest->id]) }}" class="bg-white p-5 rounded-2xl border border-gray-100 flex items-center justify-between group/dest hover:shadow-xl hover:shadow-gold-500/5 transition-all duration-500">
                            <div class="flex items-center gap-4">
                                <div class="w-2 h-2 bg-gold-500 rounded-full group-hover/dest:scale-150 transition-transform"></div>
                                <span class="font-black text-gray-800 group-hover/dest:text-gold-600 transition-colors uppercase tracking-tight text-sm">{{ $dest->name }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover/dest:text-gold-500 transform group-hover/dest:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        <a href="{{ route('tours.index', ['view' => 'destinations']) }}" class="inline-flex items-center gap-3 text-gold-600 font-black uppercase tracking-widest text-xs hover:text-gold-700 transition-all py-3 px-8 bg-white rounded-full shadow-sm border border-gold-100 hover:shadow-md">
                            Explore All Destinations
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 5. WHY CHOOSE US --}}
<section class="py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="max-w-4xl mb-20">
            <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">The Twina Edge</span>
            <h2 class="font-display text-4xl md:text-6xl font-black text-safari-dark mb-8">Why Choose Twina Safaris?</h2>
            <p class="text-gray-700 text-xl font-light leading-relaxed italic border-l-4 border-gold-500 pl-8">
                At Twina Safaris, we believe that every traveler deserves an authentic, comfortable, and unforgettable African adventure. Our passion for Tanzania, combined with our commitment to exceptional service, allows us to create journeys that go beyond ordinary tourism.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            {{-- Column 1 --}}
            <div class="space-y-12">
                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Expertise</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Expert Local Guides</h3>
                    <p class="text-gray-600 leading-relaxed font-light">Our professional guides are the heart of every safari experience. With extensive knowledge of Tanzania's wildlife, ecosystems, cultures, and hidden gems, they bring each destination to life. Their expertise ensures you enjoy memorable wildlife sightings, fascinating insights, and a safe journey throughout your adventure.</p>
                </div>

                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Customization</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Personalized Safari Experiences</h3>
                    <p class="text-gray-600 leading-relaxed font-light">No two travelers are the same, and neither should their journeys be. We take time to understand your interests, travel style, and budget to create a customized itinerary that perfectly matches your expectations. From luxury safaris to budget-friendly adventures, every trip is designed around you.</p>
                </div>

                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Integrity</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Competitive and Transparent Pricing</h3>
                    <p class="text-gray-600 leading-relaxed font-light">We believe in providing excellent value without compromising quality. Our pricing is clear and transparent, with no hidden costs or unexpected charges. You receive carefully selected accommodations, reliable transportation, and exceptional service at a fair and competitive price.</p>
                </div>

                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Reliability</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Commitment to Safety and Comfort</h3>
                    <p class="text-gray-600 leading-relaxed font-light">Your well-being is our highest priority. We maintain high safety standards across all our tours and work with trusted partners to ensure reliable vehicles, quality accommodations, and professional support throughout your journey. From arrival to departure, we strive to make your experience comfortable and worry-free.</p>
                </div>
            </div>

            {{-- Column 2 --}}
            <div class="space-y-12">
                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Responsibility</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Responsible and Sustainable Tourism</h3>
                    <p class="text-gray-600 leading-relaxed font-light">We are dedicated to preserving Tanzania's natural beauty and supporting local communities. By promoting responsible tourism practices, we help protect wildlife habitats, support conservation efforts, and create positive economic opportunities for local people. Traveling with Twina Safaris means contributing to a more sustainable future for Tanzania.</p>
                </div>

                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Support</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">24/7 Customer Support</h3>
                    <p class="text-gray-600 leading-relaxed font-light">Travel plans can sometimes require flexibility and quick assistance. Our dedicated support team is available around the clock to answer questions, provide guidance, and assist with any concerns before, during, or after your trip. We are always just a message or phone call away.</p>
                </div>

                <div class="group">
                    <h4 class="text-gold-600 text-xs font-black uppercase tracking-[0.2em] mb-3">Craftsmanship</h4>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Tailor-Made Itineraries</h3>
                    <p class="text-gray-600 leading-relaxed font-light">Whether you dream of witnessing the Great Migration in the Serengeti, exploring the Ngorongoro Crater, conquering Mount Kilimanjaro, or relaxing on Zanzibar's white-sand beaches, we create itineraries that fit your schedule, interests, and travel goals. Every journey is carefully crafted to deliver the best possible experience.</p>
                </div>

                <div class="bg-safari-dark p-10 rounded-[3rem] text-white">
                    <h3 class="font-display text-3xl font-bold mb-4 text-gold-400">Discover Tanzania</h3>
                    <p class="font-light text-gray-300 mb-6">Experience Africa. Create Memories That Last a Lifetime. Every journey is more than a vacation—it's an opportunity to connect with nature.</p>
                    <a href="{{ route('tours.index') }}" class="btn-gold px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest inline-block">Explore Packages</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 6. THE BIG FIVE SHOWCASE --}}
<section class="py-24 bg-safari-dark overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-400 text-sm font-black uppercase tracking-[0.4em] mb-4 block">The Soul of the Safari</span>
            <h2 class="font-display text-4xl md:text-6xl font-black text-white mb-6">Meet the Big Five</h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg font-light italic">"Tanzania is one of the few places on earth where you can witness all five of Africa's most legendary animals in their natural habitat."</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach([
                ['name' => 'Lion', 'img' => asset('images/big-five/lion.jpg')],
                ['name' => 'Elephant', 'img' => asset('images/big-five/elephant.jpg')],
                ['name' => 'Leopard', 'img' => asset('images/big-five/leopard.jpg')],
                ['name' => 'Buffalo', 'img' => asset('images/big-five/buffalo.jpg')],
                ['name' => 'Rhino', 'img' => asset('images/big-five/rhino.jpg')]
            ] as $animal)
            <div class="group relative h-[450px] overflow-hidden rounded-2xl shadow-2xl">
                <img src="{{ $animal['img'] }}" alt="{{ $animal['name'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    <h3 class="text-2xl font-display font-bold text-white mb-2 tracking-widest uppercase">{{ $animal['name'] }}</h3>
                    <div class="w-10 h-0.5 bg-gold-500 mx-auto opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('tours.index', ['category' => 1]) }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black shadow-2xl inline-flex items-center gap-3 hover:scale-105 transition-all">
                Book Your Wildlife Safari
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection
