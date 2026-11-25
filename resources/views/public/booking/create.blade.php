@php
    $countries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
        "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
        "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic",
        "Denmark", "Djibouti", "Dominica", "Dominican Republic",
        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
        "Fiji", "Finland", "France",
        "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemalan", "Guinea", "Guinea-Bissau", "Guyana",
        "Haiti", "Honduras", "Hungary",
        "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy",
        "Jamaica", "Japan", "Jordan",
        "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan",
        "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
        "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Mexico", "Monaco", "Mongolia", "Morocco", "Mozambique", "Myanmar",
        "Namibia", "Nepal", "Netherlands", "New Zealand", "Nigeria", "Norway",
        "Oman",
        "Pakistan", "Palestine", "Panama", "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
        "Qatar",
        "Romania", "Russia", "Rwanda",
        "Saudi Arabia", "Senegal", "Singapore", "South Africa", "Spain", "Sri Lanka", "Sweden", "Switzerland",
        "Tanzania", "Thailand", "Tunisia", "Turkey",
        "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan",
        "Vatican City", "Venezuela", "Vietnam",
        "Yemen",
        "Zambia", "Zimbabwe"
    ];
@endphp

@extends('public.layouts.app')

@section('title', 'Book ' . $tour->title . ' - Twina Safaris')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Breadcrumbs --}}
        <nav class="flex mb-8 text-sm text-gray-500 font-medium uppercase tracking-widest">
            <a href="{{ route('home') }}" class="hover:text-gold-600 transition-colors">{{ __('Home') }}</a>
            <span class="mx-3 text-gray-300">/</span>
            <a href="{{ route('tours.index') }}" class="hover:text-gold-600 transition-colors">{{ __('Tours') }}</a>
            <span class="mx-3 text-gray-300">/</span>
            <span class="text-gray-900">{{ __('Booking Confirmation') }}</span>
        </nav>

        {{-- One Alpine component owns the estimate so the form controls and the
             sticky price summary always read the same live state. --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12" x-data="{
                adults: {{ old('number_of_adults', 1) }},
                children: {{ old('number_of_children', 0) }},
                tourPrice: {{ \App\Helpers\Currency::convert((float) $tour->price) }},
                childPrice: {{ \App\Helpers\Currency::convert((float) ($tour->child_price ?? ($tour->price * 0.7))) }},
                depositPct: {{ \App\Models\Booking::DEPOSIT_PERCENTAGE }},
                selectedPackageId: {{ !empty($selectedPackageId) ? (int) $selectedPackageId : 'null' }},
                selectedRoomTypeId: {{ !empty($selectedRoomTypeId) ? (int) $selectedRoomTypeId : 'null' }},
                packageOptions: @js($packageOptions),
                roomSupplements: @js($roomSupplements),
                selectedExtrasQty: {},
                extrasList: @js($extras),
                symbol: '{{ $symbol }}',
                decimals: {{ $decimals }},
                money(amount) {
                    return this.symbol + Number(amount || 0).toLocaleString('en-US', {
                        minimumFractionDigits: this.decimals,
                        maximumFractionDigits: this.decimals
                    });
                },
                get totalGuests() { return Math.max(1, (parseInt(this.adults || 0) + parseInt(this.children || 0))); },
                get activePackage() {
                    return (this.packageOptions || []).find(p => String(p.id) === String(this.selectedPackageId)) || null;
                },
                get activeAdultPrice() { return this.activePackage ? parseFloat(this.activePackage.adult_price || 0) : this.tourPrice; },
                get activeChildPrice() { return this.activePackage ? parseFloat(this.activePackage.child_price || 0) : this.childPrice; },
                get roomSupplement() {
                    if (!this.selectedRoomTypeId) return 0;
                    const pkg = this.activePackage;
                    if (pkg && Object.prototype.hasOwnProperty.call(pkg.room_supplements || {}, this.selectedRoomTypeId)) {
                        return parseFloat((pkg.room_supplements || {})[this.selectedRoomTypeId] || 0);
                    }
                    return parseFloat((this.roomSupplements || {})[this.selectedRoomTypeId] || 0);
                },
                get baseCost() {
                    return (parseInt(this.adults || 0) * this.activeAdultPrice)
                        + (parseInt(this.children || 0) * this.activeChildPrice);
                },
                findExtra(id) {
                    return (this.extrasList || []).find(e => String(e.id) === String(id) || e.name === id) || null;
                },
                defaultExtraQty(id) {
                    const ext = this.findExtra(id);
                    return (ext && ext.price_type === 'per_group') ? 1 : this.totalGuests;
                },
                toggleExtraById(id) {
                    const key = String(id);
                    const curr = parseInt((this.selectedExtrasQty && this.selectedExtrasQty[key]) || 0);
                    const nextQty = curr > 0 ? 0 : this.defaultExtraQty(id);
                    this.selectedExtrasQty = Object.assign({}, this.selectedExtrasQty, { [key]: nextQty });
                },
                setExtraQty(id, val) {
                    const key = String(id);
                    const qty = parseInt(val || 0);
                    this.selectedExtrasQty = Object.assign({}, this.selectedExtrasQty, { [key]: qty });
                },
                isExtraSelected(id) {
                    const key = String(id);
                    return parseInt((this.selectedExtrasQty && this.selectedExtrasQty[key]) || 0) > 0;
                },
                getExtraQty(id) {
                    const key = String(id);
                    return parseInt((this.selectedExtrasQty && this.selectedExtrasQty[key]) || 1);
                },
                get selectedExtraLines() {
                    return Object.keys(this.selectedExtrasQty || {})
                        .map(key => {
                            const qty = parseInt(this.selectedExtrasQty[key] || 0);
                            const ext = qty > 0 ? this.findExtra(key) : null;
                            if (!ext) return null;

                            const price = parseFloat(ext.price || 0);
                            const perGroup = (ext.price_type || 'per_person') === 'per_group';
                            const chargeQty = perGroup ? 1 : qty;

                            return {
                                id: key,
                                name: ext.name || ext.title || '',
                                detail: perGroup
                                    ? '{{ addslashes(__('per Group')) }}'
                                    : (qty + ' × ' + this.money(price)),
                                lineTotal: price * chargeQty
                            };
                        })
                        .filter(Boolean);
                },
                get extrasCost() {
                    return this.selectedExtraLines.reduce((sum, line) => sum + line.lineTotal, 0);
                },
                get countSelectedExtras() { return this.selectedExtraLines.length; },
                get totalPrice() { return this.baseCost + this.roomSupplement + this.extrasCost; },
                get depositRequired() { return (this.totalPrice * this.depositPct) / 100; },
                get balanceDue() { return Math.max(0, this.totalPrice - this.depositRequired); }
            }">
            {{-- Left Side: Booking Form --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-safari-dark p-8 text-white">
                        <h1 class="font-display text-3xl font-bold mb-2">{{ __('Finalize Your Booking') }}</h1>
                        <p class="text-white/60">{{ __('Complete the details below to secure your Tanzanian adventure.') }}</p>
                    </div>

                    <form action="{{ route('booking.store', $tour->slug) }}" method="POST" class="p-8">
                        @csrf
                        <input type="hidden" name="tier" value="{{ old('tier', request('tier')) }}">

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm font-bold">1</span>
                                {{ __('Personal Information') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Email Address') }}</label>
                                    <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Phone Number') }}</label>
                                    <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div class="md:col-span-2" x-data="{
                                    open: false,
                                    search: '{{ old('nationality') }}',
                                    countries: @js($countries),
                                    get filteredCountries() {
                                        if (this.search === '') return this.countries;
                                        return this.countries.filter(item => {
                                            return item.toLowerCase().includes(this.search.toLowerCase())
                                        })
                                    },
                                    select(val) {
                                        this.search = val;
                                        this.open = false;
                                    }
                                }">
                                    <label for="country" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Nationality') }}</label>
                                    <div class="relative">
                                        <input type="text"
                                               name="nationality"
                                               id="country"
                                               required
                                               x-model="search"
                                               @focus="open = true"
                                               @click.away="open = false"
                                               @keydown.escape="open = false"
                                               placeholder="{{ __('Select your country...') }}"
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-12 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">

                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                            <svg class="w-5 h-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>

                                        <div x-show="open"
                                             x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-60 overflow-y-auto">
                                            <template x-for="country in filteredCountries" :key="country">
                                                <button type="button"
                                                        @click="select(country)"
                                                        class="w-full text-left px-6 py-3 text-sm text-gray-700 hover:bg-gold-50 hover:text-gold-700 transition-colors border-b border-gray-50 last:border-0"
                                                        x-text="country">
                                                </button>
                                            </template>
                                            <div x-show="filteredCountries.length === 0" class="px-6 py-4 text-sm text-gray-400 italic">
                                                {{ __('No matches? Feel free to type manually...') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: TRAVEL DATES & GUESTS --}}
                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm font-bold">2</span>
                                {{ __('Travel Dates & Guests') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Travel Date') }}</label>
                                    <input type="date" name="travel_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('travel_date') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('No. of Adults') }}</label>
                                    <input type="number" name="number_of_adults" required min="1" x-model="adults" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('No. of Children') }}</label>
                                    <input type="number" name="number_of_children" min="0" x-model="children" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- STEP 3: CHOOSE ACCOMMODATION PACKAGE & ROOM TYPE --}}
                        @if(isset($packages) && count($packages) > 0)
                        <div class="mb-10 pt-4 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm font-bold">3</span>
                                {{ __('Select Accommodation Package') }}
                            </h3>

                            <div class="grid grid-cols-1 gap-4 mb-6">
                                @foreach($packages as $pkg)
                                <label class="relative flex flex-col p-6 rounded-2xl border-2 cursor-pointer transition-all shadow-xs"
                                       :class="selectedPackageId == {{ $pkg->id }} ? 'border-amber-500 bg-amber-50/20' : 'border-gray-200 bg-white hover:border-gray-300'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="tour_package_id" value="{{ $pkg->id }}" x-model="selectedPackageId" class="w-5 h-5 text-amber-600 focus:ring-amber-500">
                                            <span class="font-bold text-gray-900 text-base">{{ $pkg->name }}</span>
                                        </div>
                                        <span class="font-black text-amber-700 text-lg">{{ $pkg->formatted_selling_price }} / person</span>
                                    </div>

                                    @if($pkg->description)
                                    <p class="text-xs text-gray-600 mb-4 pl-8">{{ $pkg->description }}</p>
                                    @endif

                                    {{-- Nightly Hotel Breakdown --}}
                                    @if($pkg->itineraryDays->count() > 0)
                                    <div class="pl-8 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-stone-700">
                                        @foreach($pkg->itineraryDays as $day)
                                        <div class="flex items-start gap-2 bg-white/80 p-2 rounded-lg border border-gray-100">
                                            <span class="font-bold text-amber-800 shrink-0">Day {{ $day->day_number }}:</span>
                                            <span class="truncate font-semibold">{{ $day->accommodation->name ?? 'Standard Lodge' }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </label>
                                @endforeach
                            </div>

                            {{-- Room Preference Selection --}}
                            @if(isset($roomTypes) && count($roomTypes) > 0)
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Room Preference') }}</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($roomTypes as $rt)
                                    <label class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                                           :class="selectedRoomTypeId == {{ $rt->id }} ? 'border-amber-500 bg-amber-50/30 font-bold' : 'border-gray-200 bg-gray-50'">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-gray-900 font-bold">{{ $rt->name }}</span>
                                            <input type="radio" name="room_type_id" value="{{ $rt->id }}" x-model="selectedRoomTypeId" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                                        </div>
                                        <span class="text-[10px] text-gray-500">Max {{ $rt->max_occupants }} Guests</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- SECTION 3: ADD EXTRAS (LUXURY INTERACTIVE CARDS WITH CHECKBOX & REAL-TIME PRICE UPDATE) --}}
                        @if(!empty($tour->extras) && is_array($tour->extras) && count($tour->extras) > 0)
                        <div class="mb-10 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm font-bold">3</span>
                                    <div>
                                        <h3 class="text-xl font-black text-gray-900 font-display tracking-tight">{{ __('Add optional extras') }}</h3>
                                        <p class="text-xs text-stone-500 font-medium mt-0.5">{{ __('Select add-ons to customize your safari experience.') }}</p>
                                    </div>
                                </div>

                                <span class="text-xs font-bold text-amber-800 bg-amber-50 px-3 py-1 rounded-full border border-amber-200"
                                      x-show="countSelectedExtras > 0"
                                      x-text="countSelectedExtras + ' Extra(s) Selected (+ ' + symbol + Number(extrasCost).toFixed(decimals) + ')'">
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach($extras as $eIndex => $ext)
                                    @php
                                        $extId = $ext['id'] ?? 'ext_' . $eIndex;
                                        $exName = is_array($ext) ? ($ext['name'] ?? $ext['title'] ?? '') : $ext;
                                        $exDesc = is_array($ext) ? ($ext['description'] ?? '') : '';
                                        $exPrice = (float)($ext['price'] ?? 0);
                                        $exType = is_array($ext) ? ($ext['price_type'] ?? 'per_person') : 'per_person';
                                        $unitLabel = ($exType === 'per_group') ? __('per Group') : __('per Traveler');
                                    @endphp
                                    @if(!empty($exName))
                                    <div x-data="{ expanded: false }"
                                         class="p-5 rounded-2xl border transition-all duration-200 shadow-2xs"
                                         :class="isExtraSelected('{{ $extId }}') ? 'bg-amber-50/80 border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'bg-white border-stone-200 hover:border-amber-400/60'">

                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            {{-- Left Checkbox & Text --}}
                                            <div class="flex items-start gap-3.5 flex-1">
                                                {{-- Hidden input for form submission --}}
                                                <input type="hidden"
                                                       name="selected_extras_qty[{{ $extId }}]"
                                                       :value="isExtraSelected('{{ $extId }}') ? getExtraQty('{{ $extId }}') : 0">

                                                <button type="button"
                                                        @click="toggleExtraById('{{ $extId }}')"
                                                        class="mt-0.5 w-6 h-6 rounded-lg border flex items-center justify-center transition-all shrink-0 cursor-pointer"
                                                        :class="isExtraSelected('{{ $extId }}') ? 'bg-amber-500 border-amber-600 text-stone-950 font-black shadow-xs' : 'bg-stone-100 border-stone-300 text-transparent hover:border-amber-400'">
                                                    ✓
                                                </button>

                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <h4 class="font-bold text-stone-900 text-base leading-snug cursor-pointer hover:text-amber-800 transition-colors" @click="toggleExtraById('{{ $extId }}')">
                                                            {{ $exName }}
                                                        </h4>
                                                        <span x-show="isExtraSelected('{{ $extId }}')" class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            ✓ Added
                                                        </span>
                                                    </div>

                                                    @if(!empty($exDesc))
                                                        <div class="text-xs text-stone-600 leading-relaxed font-normal">
                                                            <template x-if="!expanded && '{{ addslashes($exDesc) }}'.length > 130">
                                                                <span>
                                                                    {{ Str::limit($exDesc, 130) }}
                                                                    <button type="button" @click="expanded = true" class="text-amber-800 hover:underline font-bold ml-1 cursor-pointer">Read more</button>
                                                                </span>
                                                            </template>
                                                            <template x-if="expanded || '{{ addslashes($exDesc) }}'.length <= 130">
                                                                <span>
                                                                    {{ $exDesc }}
                                                                    <button type="button" x-show="expanded && '{{ addslashes($exDesc) }}'.length > 130" @click="expanded = false" class="text-amber-800 hover:underline font-bold ml-1 cursor-pointer">Show less</button>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Right Price & Selection Controls --}}
                                            <div class="flex flex-wrap items-center justify-between md:justify-end gap-3 shrink-0 border-t md:border-t-0 border-stone-100 pt-3 md:pt-0">
                                                <div class="text-left md:text-right">
                                                    <div class="text-base sm:text-lg font-black text-stone-900">{{ $symbol }}{{ number_format($exPrice, $decimals) }}</div>
                                                    <div class="text-[10px] font-bold text-stone-500 uppercase tracking-wider">{{ $unitLabel }}</div>
                                                </div>

                                                {{-- Quantity Selector (per-person extras only) --}}
                                                @if($exType === 'per_group')
                                                    <span x-show="isExtraSelected('{{ $extId }}')" class="text-[10px] font-black uppercase text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">1 {{ __('Group') }}</span>
                                                @else
                                                <div x-show="isExtraSelected('{{ $extId }}')" class="flex items-center gap-2">
                                                    <select :value="getExtraQty('{{ $extId }}')"
                                                            @change="setExtraQty('{{ $extId }}', $event.target.value)"
                                                            class="bg-white border border-amber-400 rounded-xl px-3 py-2 text-xs font-black text-stone-900 outline-none focus:ring-2 focus:ring-amber-500 shadow-2xs">
                                                        <template x-for="n in totalGuests" :key="n">
                                                            <option :value="n" x-text="n + ' ' + (n > 1 ? '{{ __("Travelers") }}' : '{{ __("Traveler") }}')"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                                @endif

                                                {{-- Select/Add Toggle Button --}}
                                                <button type="button"
                                                        @click="toggleExtraById('{{ $extId }}')"
                                                        class="px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all cursor-pointer shadow-2xs flex items-center gap-1.5"
                                                        :class="isExtraSelected('{{ $extId }}') ? 'bg-amber-500 text-stone-950 hover:bg-amber-600' : 'bg-stone-900 hover:bg-stone-800 text-amber-400'">
                                                    <span x-text="isExtraSelected('{{ $extId }}') ? '✓ Selected' : '+ Add Extra'"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm">3</span>
                                {{ __('Pickup Location') }}
                            </h3>
                            <div class="space-y-8" x-data="{
                                selectedPoint: null,
                                points: {{ json_encode($tour->pickup_locations ?? []) }},
                                mapUrl: 'https://maps.google.com/maps?q={{ urlencode($tour->meeting_point ?? 'Kilimanjaro') }}&t=&z=13&ie=UTF8&iwloc=&output=embed',
                                updateMap(point) {
                                    this.selectedPoint = point.name;
                                    this.mapUrl = `https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(point.lng)-0.005}%2C${parseFloat(point.lat)-0.005}%2C${parseFloat(point.lng)+0.005}%2C${parseFloat(point.lat)+0.005}&layer=mapnik&marker=${point.lat}%2C${point.lng}`;
                                }
                            }">
                                {{-- Selection Grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @if(is_array($tour->pickup_locations) && count($tour->pickup_locations) > 0)
                                        @foreach($tour->pickup_locations as $pl)
                                        <label class="relative flex flex-col p-6 bg-white border-2 border-gray-100 rounded-[2rem] cursor-pointer hover:border-gold-500 transition-all has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/20 group shadow-sm">
                                            <input type="radio" name="accommodation_preference" value="{{ $pl['name'] }}"
                                                   @change="updateMap({ name: '{{ $pl['name'] }}', lat: '{{ $pl['lat'] }}', lng: '{{ $pl['lng'] }}' })"
                                                   class="absolute top-4 right-4 w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500" required>
                                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform mb-4">📍</div>
                                            <span class="block font-black text-gray-900 text-xs uppercase tracking-widest leading-tight">{{ __($pl['name']) }}</span>
                                        </label>
                                        @endforeach
                                    @else
                                        {{-- Default Fallbacks if none configured --}}
                                        <label class="relative flex items-center gap-4 p-5 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gold-500 transition-all has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/20 group shadow-sm">
                                            <input type="radio" name="accommodation_preference" value="Airport Pickup" class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500" checked required>
                                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl">✈️</div>
                                            <div>
                                                <span class="block font-black text-gray-900 text-sm uppercase tracking-tight">{{ __('Airport Pickup') }}</span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-center gap-4 p-5 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gold-500 transition-all has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/20 group shadow-sm">
                                            <input type="radio" name="accommodation_preference" value="Hotel Pickup" class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500" required>
                                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl">🏨</div>
                                            <div>
                                                <span class="block font-black text-gray-900 text-sm uppercase tracking-tight">{{ __('Hotel Pickup') }}</span>
                                            </div>
                                        </label>
                                    @endif
                                </div>

                                {{-- Live Map Preview --}}
                                <div class="bg-white p-2 rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden h-80 relative">
                                    <iframe :src="mapUrl" class="w-full h-full rounded-[2rem]" frameborder="0"></iframe>
                                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-safari-dark/90 backdrop-blur-md px-6 py-2 rounded-full text-[10px] font-black text-white uppercase tracking-[0.2em] border border-white/10" x-show="selectedPoint">
                                        {{ __('Selected') }}: <span class="text-gold-400" x-text="selectedPoint"></span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Special Requests / Flight Details') }}</label>
                                    <textarea name="special_requests" rows="4" placeholder="{{ __('e.g. Flight number, Arrival time, or Hotel name...') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">{{ old('special_requests') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm">3</span>
                                {{ __('Payment Method') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="bank_transfer" required {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ __('Direct Bank Transfer') }}</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">{{ __('SWIFT / International Wire') }}</span>
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="mpesa" {{ old('payment_method') == 'mpesa' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ __('M-Pesa') }}</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">{{ __('Vodacom Tanzania') }}</span>
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="tigopesa" {{ old('payment_method') == 'tigopesa' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ __('Tigo Pesa') }}</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">{{ __('Tigo Tanzania') }}</span>
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="airtelmoney" {{ old('payment_method') == 'airtelmoney' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ __('Airtel Money') }}</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">{{ __('Airtel Tanzania') }}</span>
                                    </div>
                                </label>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-3 leading-relaxed">
                                {{ __('Payment is completed manually after your booking is approved. Our team will send you the payment instructions and verify your transaction.') }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-start gap-3 mb-6">
                                <input type="checkbox" required class="mt-1 w-4 h-4 text-gold-600 rounded border-gray-300">
                                <p class="text-xs text-gray-500 leading-relaxed font-medium uppercase tracking-tighter">
                                    {{ __('I agree to the') }} <a href="{{ route('terms.index') }}" target="_blank" class="text-gold-600 underline">{{ __('Terms and Conditions') }}</a> {{ __('and') }} <a href="{{ route('booking.policies') }}" target="_blank" class="text-gold-600 underline">{{ __('Cancellation Policy') }}</a> {{ __('of Twina Safaris. I understand that my booking is subject to availability and will be confirmed via email.') }}
                                </p>
                            </div>
                            <button type="submit" class="w-full btn-gold py-5 rounded-2xl font-bold text-lg shadow-xl shadow-gold-500/20">
                                {{ __('Submit Booking Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Side: Tour Summary --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            {{ __('Tour Summary') }}
                        </h3>

                        <div class="flex gap-4 mb-8">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->translate('title') }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">{{ __($tour->category->name ?? 'Safari') }}</span>
                                <h4 class="font-display font-bold text-gray-900 leading-tight">{{ $tour->translate('title') }}</h4>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $tour->destination?->translate('name') ?? __('Tanzania') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pb-6 border-b border-gray-100">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">{{ __('Duration') }}</span>
                                <span class="text-gray-900 font-bold">{{ $tour->duration_days }} {{ __('Days') }} / {{ $tour->duration_nights }} {{ __('Nights') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">{{ __('Price per person') }}</span>
                                <span class="text-gray-900 font-bold" x-text="money(activeAdultPrice)">{{ $tour->display_price_formatted }}</span>
                            </div>
                            @if(!empty($selectedTier['name']))
                            <div class="flex justify-between items-center text-xs text-amber-800 font-semibold">
                                <span class="text-gray-500 font-medium">{{ __('Accommodation Level') }}</span>
                                <span class="text-right">{{ $selectedTier['name'] }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="py-6 border-t border-b border-gray-100 space-y-3">
                            <div class="flex justify-between items-center text-sm text-gray-600 font-medium">
                                <span>{{ __('Base Tour Price') }}</span>
                                <span class="font-bold text-gray-900" x-text="money(baseCost)"></span>
                            </div>
                            <div x-show="roomSupplement > 0" class="flex justify-between items-center text-sm text-stone-600 font-medium">
                                <span>{{ __('Room Supplement') }}</span>
                                <span class="font-bold text-stone-700" x-text="money(roomSupplement)"></span>
                            </div>
                            <div x-show="countSelectedExtras > 0" class="flex justify-between items-center text-sm text-amber-800 font-medium">
                                <span>{{ __('Selected Extras') }} (<span x-text="countSelectedExtras"></span>)</span>
                                <span class="font-bold text-amber-800" x-text="'+' + money(extrasCost)"></span>
                            </div>

                            {{-- Itemised add-ons, shown as soon as a guest adds an extra. --}}
                            <template x-if="countSelectedExtras > 0">
                                <div class="rounded-xl bg-amber-50/60 border border-amber-100 px-3 py-2.5 space-y-2">
                                    <template x-for="line in selectedExtraLines" :key="line.id">
                                        <div class="flex items-start justify-between gap-3 text-[11px] text-amber-900">
                                            <span class="font-semibold leading-snug">
                                                <span x-text="line.name"></span>
                                                <span class="block text-[10px] font-bold text-stone-400" x-text="line.detail"></span>
                                            </span>
                                            <span class="font-bold shrink-0" x-text="money(line.lineTotal)"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div class="flex justify-between items-center pt-2">
                                <span class="text-base font-bold text-gray-900">{{ __('Estimated Total') }}</span>
                                <span class="text-2xl font-display font-bold text-gold-600" x-text="money(totalPrice)">{{ $tour->display_price_formatted }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-stone-500 pt-1 font-medium">
                                <span>{{ __('Deposit Required') }} (<span x-text="depositPct"></span>%)</span>
                                <span class="font-bold text-amber-700" x-text="money(depositRequired)"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-stone-500 font-medium">
                                <span>{{ __('Remaining Balance') }} ({{ 100 - (int) \App\Models\Booking::DEPOSIT_PERCENTAGE }}% · {{ __('65 days before departure') }})</span>
                                <span class="font-bold text-stone-700" x-text="money(balanceDue)"></span>
                            </div>
                        </div>

                        <div class="bg-gold-50 p-4 rounded-2xl flex items-start gap-3">
                            <div class="text-gold-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed font-medium uppercase tracking-tighter">
                                {{ __('After clicking confirm, you\'ll receive instructions on how to complete your deposit payment via your chosen method.') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">{{ __('Need help booking?') }}</p>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone', '+255795482197') }}" class="text-lg font-bold text-gray-900 hover:text-gold-600 transition-colors flex items-center justify-center gap-2">
                            {{ \App\Models\Setting::get('site_phone', '+255 795 482 197') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
