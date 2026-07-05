@extends('admin.layouts.app')

@section('title', 'Create Tour')
@section('page-title', 'Create Tour')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="font-medium text-red-800">Please fix the following errors:</h4>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
                <button type="button" onclick="showTab('basic')" id="tab-basic" class="tab-btn pb-4 border-b-2 font-medium text-sm border-[#D4AF37] text-[#D4AF37]">Basic Info</button>
                <button type="button" onclick="showTab('pricing')" id="tab-pricing" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Pricing</button>
                <button type="button" onclick="showTab('duration')" id="tab-duration" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Duration</button>
                <button type="button" onclick="showTab('experience')" id="tab-experience" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Experience</button>
                <button type="button" onclick="showTab('itinerary')" id="tab-itinerary" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Itinerary</button>
                <button type="button" onclick="showTab('inclusions')" id="tab-inclusions" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Inclusions/Exclusions</button>
                <button type="button" onclick="showTab('faqs')" id="tab-faqs" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">FAQs</button>
                <button type="button" onclick="showTab('media')" id="tab-media" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Media</button>
                <button type="button" onclick="showTab('availability')" id="tab-availability" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Availability</button>
                <button type="button" onclick="showTab('marketing')" id="tab-marketing" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Marketing</button>
                <button type="button" onclick="showTab('seo')" id="tab-seo" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">SEO</button>
                <button type="button" onclick="showTab('logistics')" id="tab-logistics" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Logistics</button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div id="content-basic" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Package Title</label>
                    <input type="text" id="tour-title" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" id="tour-slug" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from title</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                    <select name="destination_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Destination</option>
                        @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>{{ $dest->name }}, {{ $dest->country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Point</label>
                    <input type="text" name="meeting_point" value="{{ old('meeting_point') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="e.g. Arusha Airport or Hotel Lobby">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('short_description') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                <textarea name="description" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Highlights</label>
                <div id="highlights-container" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="text" name="highlights[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <button type="button" onclick="addHighlight()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-pricing" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (USD / TZS)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Child Price</label>
                    <input type="number" step="0.01" name="child_price" value="{{ old('child_price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Group Discount</label>
                    <input type="number" step="0.01" name="group_discount" value="{{ old('group_discount') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deposit (%)</label>
                    <input type="number" name="deposit_percent" value="{{ old('deposit_percent') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="TZS" {{ old('currency') == 'TZS' ? 'selected' : '' }}>TZS</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="content-destination" class="hidden">
        </div>
        <div id="content-duration" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Days</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Nights</label>
                    <input type="number" name="duration_nights" value="{{ old('duration_nights') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
            </div>
        </div>
        <div id="content-experience" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                    <select name="difficulty_level" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tour Type</label>
                    <select name="tour_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="private" {{ old('tour_type') == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="group" {{ old('tour_type') == 'group' ? 'selected' : '' }}>Group</option>
                        <option value="luxury" {{ old('tour_type') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        <option value="budget" {{ old('tour_type') == 'budget' ? 'selected' : '' }}>Budget</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation Type</label>
                    <select name="accommodation_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="lodge" {{ old('accommodation_type') == 'lodge' ? 'selected' : '' }}>Lodge</option>
                        <option value="camping" {{ old('accommodation_type') == 'camping' ? 'selected' : '' }}>Camping</option>
                        <option value="hotel" {{ old('accommodation_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transport Type</label>
                    <select name="transport_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="4x4" {{ old('transport_type') == '4x4' ? 'selected' : '' }}>4x4 Jeep</option>
                        <option value="flight" {{ old('transport_type') == 'flight' ? 'selected' : '' }}>Flight</option>
                        <option value="boat" {{ old('transport_type') == 'boat' ? 'selected' : '' }}>Boat</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="content-itinerary" class="tab-content hidden">
            <div x-data="{ mode: 'builder' }">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <button type="button" @click="mode = 'builder'" :class="mode === 'builder' ? 'bg-white shadow-sm text-amber-600' : 'text-gray-500'" class="px-4 py-2 rounded-lg text-xs font-bold uppercase transition-all">Manual Builder</button>
                        <button type="button" @click="mode = 'code'" :class="mode === 'code' ? 'bg-white shadow-sm text-amber-600' : 'text-gray-500'" class="px-4 py-2 rounded-lg text-xs font-bold uppercase transition-all">Magic Code Mode (ChatGPT)</button>
                    </div>
                    <button x-show="mode === 'builder'" type="button" id="add-itinerary-day" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg text-sm transition-colors">+ Add Day</button>
                </div>

                {{-- Manual Builder UI --}}
                <div x-show="mode === 'builder'" id="itinerary-container" class="space-y-4 mb-6"></div>

                {{-- Magic Code Mode UI --}}
                <div x-show="mode === 'code'" x-cloak class="space-y-4">
                    <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-2xl text-xs text-indigo-800">
                        <p class="font-black mb-3 uppercase tracking-widest flex items-center gap-2">
                            <span class="text-lg">🚀</span> Super Magic Mode (All-in-One)
                        </p>
                        <p class="mb-4 leading-relaxed">Unaweza kubandika kodi moja inayojumuisha <strong>Itinerary</strong>, <strong>Inclusions</strong>, <strong>Exclusions</strong>, na <strong>FAQs</strong> kwa pamoja. ChatGPT atakupa kila kitu kwa mpigo mmoja!</p>
                        <div class="bg-white/50 p-3 rounded-lg font-mono text-[10px]">
                            { "itinerary": [...], "inclusions": [...], "exclusions": [...], "faqs": [...] }
                        </div>
                    </div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Raw JSON Package Data</label>
                    <textarea name="itinerary_raw" rows="20" class="w-full font-mono text-xs bg-gray-900 text-green-400 p-6 rounded-2xl focus:outline-none border-none shadow-2xl" placeholder="Paste the full JSON object from ChatGPT here...">{
  "itinerary": [
    {
      "title": "Day 1: Arrival & Briefing",
      "description": "Pickup from the airport and transfer to your hotel for briefing...",
      "accommodation": "Luxury Safari Lodge",
      "meals": "Lunch, Dinner",
      "distance": "10km",
      "hiking_time": "3-4 hours",
      "habitat": "Rainforest",
      "elevation": "1800m to 2500m",
      "activities": ["Airport Pickup", "Briefing", "City Tour"]
    }
  ],
  "highlights": [
    "Witness the Great Migration",
    "Stay in luxury tented camps",
    "Expert-led game drives"
  ],
  "inclusions": [
    "Professional English-speaking guide",
    "4x4 Safari Land Cruiser",
    "All Park fees and VAT",
    "Bottled drinking water"
  ],
  "exclusions": [
    "International Flights",
    "Tips for guides and porters",
    "Personal insurance"
  ],
  "faqs": [
    {
      "question": "What is the best time to visit?",
      "answer": "June to October is the peak season for wildlife viewing."
    }
  ],
  "what_to_bring": [
    "Sunscreen",
    "Binoculars",
    "Comfortable hiking boots"
  ],
  "good_to_know": [
    "Yellow Fever vaccination is required",
    "Tipping is customary in Tanzania"
  ]
}</textarea>
                </div>
            </div>
        </div>
        <div id="content-inclusions" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What's Included</label>
                    <div id="inclusions-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="inclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addInclusion()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What's Not Included</label>
                    <div id="exclusions-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="exclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addExclusion()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What to Bring</label>
                    <div id="what-to-bring-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="what_to_bring[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addWhatToBring()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Good to Know</label>
                    <div id="good-to-know-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="good_to_know[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addGoodToKnow()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-faqs" class="tab-content hidden">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tour FAQs</h3>
                    <button type="button" id="add-faq" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg text-sm transition-colors">+ Add FAQ</button>
                </div>
                <div id="faqs-container" class="space-y-4">
                </div>
            </div>
        </div>
        <div id="content-media" class="tab-content hidden">
            <div class="mb-8 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Main Featured Image</h3>

                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-8 bg-white" id="featured-image-container">
                    <img id="featured-preview" src="" class="hidden w-full max-w-md h-64 object-cover rounded-xl mb-4 shadow-md">
                    <div id="featured-placeholder" class="text-center py-10">
                        <div class="text-4xl mb-2">📸</div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No Image Selected</p>
                    </div>

                    <input type="hidden" name="featured_image" id="featured_image_path">

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'featured_image_path', previewId: 'featured-preview'}}))"
                                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                            Choose from Library
                        </button>
                        <p class="text-[10px] text-gray-400 self-center">OR</p>
                        <input type="file" name="featured_image_upload" class="text-xs file:bg-gray-100 file:border-none file:px-4 file:py-2 file:rounded-lg file:font-black file:uppercase">
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-4 italic">Tip: Use the Library to pick images that are already optimized for WebP and SEO.</p>
            </div>

            <div class="mb-8 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Gallery Images (Multiple)</h3>

                <div x-data="{ libraryImages: [] }" class="space-y-4">
                    <div class="flex flex-wrap gap-3 mb-4" x-show="libraryImages.length > 0">
                        <template x-for="(img, index) in libraryImages" :key="index">
                            <div class="relative w-24 h-24">
                                <img :src="'/storage/' + img" class="w-full h-full object-cover rounded-lg shadow-md border border-amber-500">
                                <input type="hidden" name="gallery_library[]" :value="img">
                                <button type="button" @click="libraryImages.splice(index, 1)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-3">
                        <button type="button"
                                @click="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'temp_gallery_path', previewId: 'temp_gallery_preview'}}))"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                            Add from Library
                        </button>
                        {{-- Hidden elements for picker logic --}}
                        <input type="hidden" id="temp_gallery_path" @change="libraryImages.push($el.value); $el.value = ''">
                        <img id="temp_gallery_preview" src="" class="hidden">

                        <div class="h-10 w-px bg-gray-200 mx-1"></div>

                        <input type="file" name="gallery_images[]" id="gallery_upload_input" multiple accept="image/*" class="hidden" onchange="updateGalleryUploadCount(this)">
                        <button type="button" onclick="document.getElementById('gallery_upload_input').click()" class="bg-white border border-gray-200 text-gray-500 px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-50">
                            Upload New Files <span id="gallery-upload-count" class="ml-1 text-amber-600"></span>
                        </button>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-4 italic">Tip: You can pick images from the library OR upload multiple new files. We recommend 4-5 high-quality images.</p>
            </div>

            <div class="mb-6 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Video Content</h3>
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube/Vimeo URL</label>
                <input type="url" name="video_url" value="{{ old('video_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="https://www.youtube.com/watch?v=...">
            </div>
        </div>
        <div id="content-availability" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Slots</label>
                    <input type="number" name="available_slots" value="{{ old('available_slots') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seasonal Tag</label>
                    <select name="seasonal_tag" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="high" {{ old('seasonal_tag') == 'high' ? 'selected' : '' }}>High Season</option>
                        <option value="low" {{ old('seasonal_tag') == 'low' ? 'selected' : '' }}>Low Season</option>
                        <option value="year-round" {{ old('seasonal_tag') == 'year-round' ? 'selected' : '' }}>Year-round</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Deadline (Days)</label>
                    <input type="number" name="booking_deadline_days" value="{{ old('booking_deadline_days') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Availability Notes</label>
                    <textarea name="availability_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('availability_notes') }}</textarea>
                </div>
            </div>
        </div>
        <div id="content-marketing" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2 flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Published</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Featured Package</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Bestseller</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_new" {{ old('is_new') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">New Package</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="limited_offer" {{ old('limited_offer') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Limited Offer</span>
                    </label>
                </div>
            </div>
        </div>
        <div id="content-seo" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('meta_description') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <textarea name="meta_keywords" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('meta_keywords') }}</textarea>
                </div>
            </div>
        </div>
        <div id="content-logistics" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure Location</label>
                    <input type="text" name="departure_location" value="{{ old('departure_location') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                    <input type="text" name="departure_time" value="{{ old('departure_time') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2 flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="pickup_included" {{ old('pickup_included') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Pickup Included</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="airport_pickup" {{ old('airport_pickup') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Airport Pickup</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="transport_included" {{ old('transport_included') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Transport Included</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                    <input type="text" id="location-name" name="location_name" value="{{ old('location_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="button" id="generate-map" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Generate Map Iframe</button>
                    <button type="button" id="find-coords" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Find Coordinates</button>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Map Iframe</label>
                    <textarea id="map-location" name="map_location" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('map_location') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <div id="map-preview" class="w-full h-64 border border-gray-300 rounded-lg overflow-hidden"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Guide</label>
                    <input type="text" name="assigned_guide" value="{{ old('assigned_guide') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Age</label>
                    <input type="number" name="min_age" value="{{ old('min_age') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Age</label>
                    <input type="number" name="max_age" value="{{ old('max_age') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Languages Offered</label>
                    <div id="languages-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="languages_offered[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addLanguage()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Notes</label>
                    <textarea name="special_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('special_notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('admin.tours.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Create Tour</button>
        </div>
    </form>
</div>

<script>
let dayCount = 0;

function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

const titleInput = document.getElementById('tour-title');
const slugInput = document.getElementById('tour-slug');

if (titleInput && slugInput) {
    titleInput.addEventListener('input', function() {
        if (slugInput.dataset.manual !== 'true') {
            slugInput.value = slugify(this.value);
        }
    });

    slugInput.addEventListener('input', function() {
        slugInput.dataset.manual = 'true';
        if (this.value === '') {
            slugInput.dataset.manual = 'false';
            slugInput.value = slugify(titleInput.value);
        }
    });
}

function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`content-${tabId}`).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-[#D4AF37]', 'text-[#D4AF37]');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById(`tab-${tabId}`).classList.add('border-[#D4AF37]', 'text-[#D4AF37]');
    document.getElementById(`tab-${tabId}`).classList.remove('border-transparent', 'text-gray-500');
}

document.getElementById('add-itinerary-day').addEventListener('click', function() {
    dayCount++;
    const dayHtml = `
        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-800">Day ${dayCount}</h4>
                <button type="button" class="remove-day text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day Title</label>
                    <input type="text" name="itinerary[${dayCount}][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="itinerary[${dayCount}][description]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation</label>
                    <input type="text" name="itinerary[${dayCount}][accommodation]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meals</label>
                    <input type="text" name="itinerary[${dayCount}][meals]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distance (e.g. 12 km)</label>
                    <input type="text" name="itinerary[${dayCount}][distance]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hiking Time (e.g. 5-6 hours)</label>
                    <input type="text" name="itinerary[${dayCount}][hiking_time]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Habitat (e.g. Rainforest)</label>
                    <input type="text" name="itinerary[${dayCount}][habitat]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Elevation (e.g. 1800m to 3100m)</label>
                    <input type="text" name="itinerary[${dayCount}][elevation]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activities</label>
                    <textarea name="itinerary[${dayCount}][activities]" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]"></textarea>
                </div>
            </div>
        </div>
    `;
    document.getElementById('itinerary-container').insertAdjacentHTML('beforeend', dayHtml);
});

document.getElementById('itinerary-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-day')) {
        e.target.closest('.mb-6').remove();
    }
});

function addHighlight() {
    const container = document.getElementById('highlights-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="highlights[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function addLanguage() {
    const container = document.getElementById('languages-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="languages_offered[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function addInclusion() {
    const container = document.getElementById('inclusions-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="inclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function addExclusion() {
    const container = document.getElementById('exclusions-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="exclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function addWhatToBring() {
    const container = document.getElementById('what-to-bring-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="what_to_bring[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function addGoodToKnow() {
    const container = document.getElementById('good-to-know-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="good_to_know[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
    `;
    container.appendChild(div);
}

function updateGalleryUploadCount(input) {
    const count = input.files.length;
    const label = document.getElementById('gallery-upload-count');
    label.textContent = count > 0 ? `(${count} selected)` : '';
}

let faqCount = 0;
document.getElementById('add-faq').addEventListener('click', function() {
    faqCount++;
    const faqHtml = `
        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-800">FAQ #${faqCount}</h4>
                <button type="button" onclick="this.closest('.p-4').remove()" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                    <input type="text" name="faqs[${faqCount}][question]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                    <textarea name="faqs[${faqCount}][answer]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]"></textarea>
                </div>
            </div>
        </div>
    `;
    document.getElementById('faqs-container').insertAdjacentHTML('beforeend', faqHtml);
});

document.getElementById('generate-map').addEventListener('click', function() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    if (!lat || !lng) {
        alert('Please enter both latitude and longitude!');
        return;
    }
    const iframe = `<iframe src="https://www.openstreetmap.org/export/embed.html?bbox=${lng-0.01}%2C${lat-0.01}%2C${lng+0.01}%2C${lat+0.01}&layer=mapnik&marker=${lat}%2C${lng}" width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" style="border: 1px solid black;"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=15/${lat}/${lng}">View Larger Map</a></small>`;
    document.getElementById('map-location').value = iframe;
    document.getElementById('map-preview').innerHTML = iframe;
});

document.getElementById('find-coords').addEventListener('click', async function() {
    const locationName = document.getElementById('location-name').value;
    if (!locationName) {
        alert('Please enter a location name!');
        return;
    }
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(locationName)}&format=json&limit=1`);
        const data = await response.json();
        if (data.length > 0) {
            document.getElementById('latitude').value = data[0].lat;
            document.getElementById('longitude').value = data[0].lon;
            const iframe = `<iframe src="https://www.openstreetmap.org/export/embed.html?bbox=${data[0].lon-0.01}%2C${data[0].lat-0.01}%2C${data[0].lon+0.01}%2C${data[0].lat+0.01}&layer=mapnik&marker=${data[0].lat}%2C${data[0].lon}" width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" style="border: 1px solid black;"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=${data[0].lat}&mlon=${data[0].lon}#map=15/${data[0].lat}/${data[0].lon}">View Larger Map</a></small>`;
            document.getElementById('map-location').value = iframe;
            document.getElementById('map-preview').innerHTML = iframe;
        } else {
            alert('No coordinates found for this location!');
        }
    } catch (error) {
        console.error(error);
        alert('Error finding coordinates!');
    }
});

document.getElementById('map-location').addEventListener('input', function() {
    document.getElementById('map-preview').innerHTML = this.value;
});
</script>
@endsection
