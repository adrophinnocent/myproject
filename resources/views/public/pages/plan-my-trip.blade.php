@extends('public.layouts.app')

@section('title', 'Plan My Trip - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 pt-32 pb-12">
    <div class="text-center mb-12">
        <h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">Plan Your Dream Safari</h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">Let us create a personalized itinerary just for you. Fill out the form below and our safari experts will get back to you within 24 hours.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8 text-center">
            <div class="text-green-600 font-semibold text-lg">{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('trip-plan.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Nationality *</label>
                <select name="nationality" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none appearance-none bg-white">
                    <option value="">Select your country...</option>
                    <option value="United States" {{ old('nationality') == 'United States' ? 'selected' : '' }}>🇺🇸 United States</option>
                    <option value="United Kingdom" {{ old('nationality') == 'United UK' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                    <option value="Germany" {{ old('nationality') == 'Germany' ? 'selected' : '' }}>🇩🇪 Germany</option>
                    <option value="France" {{ old('nationality') == 'France' ? 'selected' : '' }}>🇫🇷 France</option>
                    <option value="Italy" {{ old('nationality') == 'Italy' ? 'selected' : '' }}>🇮🇹 Italy</option>
                    <option value="Netherlands" {{ old('nationality') == 'Netherlands' ? 'selected' : '' }}>🇳🇱 Netherlands</option>
                    <option value="Canada" {{ old('nationality') == 'Canada' ? 'selected' : '' }}>🇨🇦 Canada</option>
                    <option value="Australia" {{ old('nationality') == 'Australia' ? 'selected' : '' }}>🇦🇺 Australia</option>
                    <option value="Spain" {{ old('nationality') == 'Spain' ? 'selected' : '' }}>🇪🇸 Spain</option>
                    <option value="Switzerland" {{ old('nationality') == 'Switzerland' ? 'selected' : '' }}>🇨🇭 Switzerland</option>
                    <option value="China" {{ old('nationality') == 'China' ? 'selected' : '' }}>🇨🇳 China</option>
                    <option value="Israel" {{ old('nationality') == 'Israel' ? 'selected' : '' }}>🇮🇱 Israel</option>
                    <option value="Tanzania" {{ old('nationality') == 'Tanzania' ? 'selected' : '' }}>🇹🇿 Tanzania</option>
                    <option value="Kenya" {{ old('nationality') == 'Kenya' ? 'selected' : '' }}>🇰🇪 Kenya</option>
                    <option value="Uganda" {{ old('nationality') == 'Uganda' ? 'selected' : '' }}>🇺🇬 Uganda</option>
                    <option value="Rwanda" {{ old('nationality') == 'Rwanda' ? 'selected' : '' }}>🇷🇼 Rwanda</option>
                    <option value="South Africa">🇿🇦 South Africa</option>
                    <option value="India">🇮🇳 India</option>
                    <option value="United Arab Emirates">🇦🇪 UAE</option>
                    <option value="Sweden">🇸🇪 Sweden</option>
                    <option value="Norway">🇳🇴 Norway</option>
                    <option value="Denmark">🇩🇰 Denmark</option>
                    <option value="Belgium">🇧🇪 Belgium</option>
                    <option value="Poland">🇵🇱 Poland</option>
                    <option value="Russia">🇷🇺 Russia</option>
                    <option value="Brazil">🇧🇷 Brazil</option>
                    <option value="Other">Other...</option>
                </select>
                @error('nationality')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-800 mb-3">Preferred Destinations</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($destinations as $destination)
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-gold-500 transition">
                        <input type="checkbox" name="destination_ids[]" value="{{ $destination->id }}" {{ in_array($destination->id, old('destination_ids', [])) ? 'checked' : '' }} class="w-4 h-4 text-gold-600">
                        <span class="text-sm text-gray-700">{{ $destination->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('destination_ids')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Travel Style</label>
                <select name="travel_style" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                    <option value="">Select...</option>
                    <option value="luxury" {{ old('travel_style') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                    <option value="mid-range" {{ old('travel_style') == 'mid-range' ? 'selected' : '' }}>Mid-Range</option>
                    <option value="budget" {{ old('travel_style') == 'budget' ? 'selected' : '' }}>Budget</option>
                    <option value="adventure" {{ old('travel_style') == 'adventure' ? 'selected' : '' }}>Adventure</option>
                </select>
                @error('travel_style')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Budget Range (per person)</label>
                <select name="budget_range" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                    <option value="">Select...</option>
                    <option value="under-2000" {{ old('budget_range') == 'under-2000' ? 'selected' : '' }}>Under $2,000</option>
                    <option value="2000-4000" {{ old('budget_range') == '2000-4000' ? 'selected' : '' }}>$2,000 - $4,000</option>
                    <option value="4000-6000" {{ old('budget_range') == '4000-6000' ? 'selected' : '' }}>$4,000 - $6,000</option>
                    <option value="over-6000" {{ old('budget_range') == 'over-6000' ? 'selected' : '' }}>Over $6,000</option>
                </select>
                @error('budget_range')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Trip Duration</label>
                <select name="duration" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                    <option value="">Select...</option>
                    <option value="3-5" {{ old('duration') == '3-5' ? 'selected' : '' }}>3-5 Days</option>
                    <option value="6-8" {{ old('duration') == '6-8' ? 'selected' : '' }}>6-8 Days</option>
                    <option value="9-12" {{ old('duration') == '9-12' ? 'selected' : '' }}>9-12 Days</option>
                    <option value="13+" {{ old('duration') == '13+' ? 'selected' : '' }}>13+ Days</option>
                </select>
                @error('duration')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Accommodation Level</label>
                <select name="accommodation_level" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                    <option value="">Select...</option>
                    <option value="5-star" {{ old('accommodation_level') == '5-star' ? 'selected' : '' }}>5 Star</option>
                    <option value="4-star" {{ old('accommodation_level') == '4-star' ? 'selected' : '' }}>4 Star</option>
                    <option value="tented-camp" {{ old('accommodation_level') == 'tented-camp' ? 'selected' : '' }}>Tented Camp</option>
                    <option value="budget" {{ old('accommodation_level') == 'budget' ? 'selected' : '' }}>Budget</option>
                </select>
                @error('accommodation_level')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-800 mb-3">Interests</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php $interests = ['Wildlife', 'Bird Watching', 'Cultural', 'Hiking', 'Beach', 'Photography', 'Family', 'Luxury']; @endphp
                @foreach($interests as $interest)
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-gold-500 transition">
                        <input type="checkbox" name="interests[]" value="{{ strtolower($interest) }}" {{ in_array(strtolower($interest), old('interests', [])) ? 'checked' : '' }} class="w-4 h-4 text-gold-600">
                        <span class="text-sm text-gray-700">{{ $interest }}</span>
                    </label>
                @endforeach
            </div>
            @error('interests')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Preferred Travel Date</label>
                <input type="date" name="travel_date" value="{{ old('travel_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('travel_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Adults *</label>
                <input type="number" name="adults" value="{{ old('adults', 1) }}" min="1" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('adults')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Children</label>
                <input type="number" name="children" value="{{ old('children', 0) }}" min="0" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">
                @error('children')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-800 mb-2">Additional Information</label>
            <textarea name="message" rows="5" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none">{{ old('message') }}</textarea>
            @error('message')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="w-full btn-gold py-4 rounded-xl font-bold text-lg">
            Send My Trip Request
        </button>
    </form>

    {{-- ========== EXPLORE TANZANIA WONDERS SECTION ========== --}}
    <div class="mt-32 pt-20 border-t border-gray-100">
        <div class="bg-safari-dark rounded-[3rem] p-12 md:p-20 relative overflow-hidden text-center shadow-2xl">
            {{-- Background Elements --}}
            <div class="absolute inset-0 opacity-10">
                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover" alt="Tanzania Background">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-amber-900/40 via-transparent to-slate-900/40"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <span class="inline-block text-gold-400 text-sm font-black uppercase tracking-[0.3em] mb-6">After having planned your trip</span>
                <h2 class="font-display text-4xl md:text-6xl text-white font-bold mb-8 leading-tight">
                    Explore <span class="italic text-gold-500">Tanzania's Wonders</span>
                </h2>
                <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-12 font-light">
                    From the vast plains of the Serengeti and the iconic Ngorongoro Crater to the majestic peaks of Mount Kilimanjaro and Zanzibar's pristine white-sand beaches. Your adventure of a lifetime is just one click away.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('tours.index') }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black shadow-2xl transition-all hover:scale-105 min-w-[220px]">
                        View Tours
                    </a>
                    <a href="{{ route('tours.index') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-safari-dark px-12 py-5 rounded-full text-lg font-black transition-all min-w-[220px]">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        {{-- Show some featured packages if available --}}
        @if(isset($inspiringTours) && $inspiringTours->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                @foreach($inspiringTours as $tour)
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all group">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-gold-50 text-gold-600 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $tour->duration_text }}</span>
                                <span class="text-gray-400 text-xs font-bold">{{ $tour->destination->name ?? 'Tanzania' }}</span>
                            </div>
                            <h3 class="font-display text-xl font-bold text-gray-900 mb-4 group-hover:text-gold-600 transition-colors">
                                <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug]) }}">{{ $tour->title }}</a>
                            </h3>
                            <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-50">
                                <div class="text-2xl font-display font-black text-safari-dark">{{ $tour->formatted_price }}</div>
                                <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug]) }}" class="w-12 h-12 rounded-full bg-safari-dark flex items-center justify-center text-white hover:bg-gold-500 transition-all shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
