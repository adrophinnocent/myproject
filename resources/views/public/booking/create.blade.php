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
            <a href="{{ route('home') }}" class="hover:text-gold-600 transition-colors">Home</a>
            <span class="mx-3 text-gray-300">/</span>
            <a href="{{ route('tours.index') }}" class="hover:text-gold-600 transition-colors">Tours</a>
            <span class="mx-3 text-gray-300">/</span>
            <span class="text-gray-900">Booking Confirmation</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- Left Side: Booking Form --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-safari-dark p-8 text-white">
                        <h1 class="font-display text-3xl font-bold mb-2">Finalize Your Booking</h1>
                        <p class="text-white/60">Complete the details below to secure your Tanzanian adventure.</p>
                    </div>

                    <form action="{{ route('booking.store', $tour->slug) }}" method="POST" class="p-8">
                        @csrf

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm">1</span>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">First Name *</label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Last Name *</label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address *</label>
                                    <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number *</label>
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
                                    <label for="country" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Select Your Country *</label>
                                    <div class="relative">
                                        <input type="text"
                                               name="nationality"
                                               id="country"
                                               required
                                               x-model="search"
                                               @focus="open = true"
                                               @click.away="open = false"
                                               @keydown.escape="open = false"
                                               placeholder="-- Choose your country or type --"
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
                                                No matches? Feel free to type manually...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm">2</span>
                                Trip Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Travel Date *</label>
                                    <input type="date" name="travel_date" required min=\"{{ date('Y-m-d', strtotime('+1 day')) }}\" value=\"{{ old('travel_date') }}\" class=\"w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all\">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">No. of Adults *</label>
                                    <input type="number" name="number_of_adults" required min="1" value="{{ old('number_of_adults', 1) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">No. of Children</label>
                                    <input type="number" name="number_of_children" min="0" value="{{ old('number_of_children', 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Special Requests / Requirements</label>
                                    <textarea name="special_requests" rows="4" placeholder="Dietary requirements, physical limitations, or special occasions..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">{{ old('special_requests') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-sm">3</span>
                                Payment Method
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="paypal" required {{ old('payment_method') == 'paypal' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">PayPal / Credit Card</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">Fast & Secure · Online Payment</span>
                                    </div>
                                    <div class="ml-auto">
                                        <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal" class="h-6">
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-4 p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-gold-500 transition-all bg-gray-50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/30">
                                    <input type="radio" name="payment_method" value="bank_transfer" required {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} class="w-5 h-5 text-gold-600 border-gray-300 focus:ring-gold-500">
                                    <div>
                                        <span class="block font-bold text-gray-900">Direct Bank Transfer</span>
                                        <span class="text-xs text-gray-500 font-medium uppercase tracking-tighter italic">Swift / Wire Transfer</span>
                                    </div>
                                    <div class="ml-auto">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v20c0 4.418 7.163 8 16 8 1.38 0 2.721-.087 4-.252V23.252c-1.279.165-2.62.252-4 .252-8.837 0-16-3.582-16-8zM40 14v20c0 4.418-7.163 8-16 8-1.38 0-2.721-.087-4-.252V23.252c1.279.165 2.62.252 4 .252 8.837 0 16-3.582 16-8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M24 4c-13.255 0-24 4.03-24 9s10.745 9 24 9 24-4.03 24-9-10.745-9-24-9z"/></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-start gap-3 mb-6">
                                <input type="checkbox" required class="mt-1 w-4 h-4 text-gold-600 rounded border-gray-300">
                                <p class="text-xs text-gray-500 leading-relaxed font-medium uppercase tracking-tighter">
                                    I agree to the <a href="#" class="text-gold-600 underline">Terms and Conditions</a> and <a href="#" class="text-gold-600 underline">Cancellation Policy</a> of Twina Safaris. I understand that my booking is subject to availability and will be confirmed via email.
                                </p>
                            </div>
                            <button type="submit" class="w-full btn-gold py-5 rounded-2xl font-bold text-lg shadow-xl shadow-gold-500/20">
                                Confirm & Proceed to Payment
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
                            Tour Summary
                        </h3>

                        <div class="flex gap-4 mb-8">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">{{ $tour->category->name ?? 'Safari' }}</span>
                                <h4 class="font-display font-bold text-gray-900 leading-tight">{{ $tour->title }}</h4>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $tour->destination->name ?? 'Tanzania' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pb-6 border-b border-gray-100">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Duration</span>
                                <span class="text-gray-900 font-bold">{{ $tour->duration_days }} Days / {{ $tour->duration_nights }} Nights</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Price per person</span>
                                <span class="text-gray-900 font-bold">{{ $tour->formatted_price }}</span>
                            </div>
                        </div>

                        <div class="py-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-bold text-gray-900">Estimated Total</span>
                                <span class="text-2xl font-display font-bold text-gold-600" id="estimated-total">{{ $tour->formatted_price }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">* Final price may vary based on group size and seasonal factors.</p>
                        </div>

                        <div class="bg-gold-50 p-4 rounded-2xl flex items-start gap-3">
                            <div class="text-gold-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed font-medium uppercase tracking-tighter">
                                After clicking confirm, you'll receive instructions on how to complete your deposit payment via your chosen method.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">Need help booking?</p>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone', '+255754000000') }}" class="text-lg font-bold text-gray-900 hover:text-gold-600 transition-colors flex items-center justify-center gap-2">
                            {{ \App\Models\Setting::get('site_phone', '+255 754 000 000') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
