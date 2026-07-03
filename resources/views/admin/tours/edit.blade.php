@extends('admin.layouts.app')

@section('title', 'Edit Tour')
@section('page-title', 'Edit Tour')

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

    <form method="POST" action="{{ route('admin.tours.update', $tour) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
                <button type="button" onclick="showTab('basic')" id="tab-basic" class="tab-btn pb-4 border-b-2 font-medium text-sm border-[#D4AF37] text-[#D4AF37]">Basic Info</button>
                <button type="button" onclick="showTab('pricing')" id="tab-pricing" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Pricing</button>
                <button type="button" onclick="showTab('destination')" id="tab-destination" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Destination</button>
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
                <button type="button" onclick="showTab('translate')" id="tab-translate" class="tab-btn pb-4 border-b-2 font-medium text-sm border-transparent text-amber-600 hover:text-amber-700 hover:border-gray-300">🌍 Translations</button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div id="content-basic" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Package Title</label>
                    <input type="text" id="tour-title" name="title" value="{{ old('title', $tour->title) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" id="tour-slug" name="slug" value="{{ old('slug', $tour->slug) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from title</p>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('short_description', $tour->short_description) }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                <textarea name="description" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description', $tour->description) }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Highlights</label>
                <div id="highlights-container" class="space-y-2">
                    @if(is_array($tour->highlights) && count($tour->highlights) > 0)
                        @foreach($tour->highlights as $highlight)
                            <div class="flex gap-2">
                                <input type="text" name="highlights[]" value="{{ is_array($highlight) ? implode(', ', $highlight) : $highlight }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                            </div>
                        @endforeach
                    @endif
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
                    <input type="number" step="0.01" name="price" value="{{ old('price', $tour->price) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Child Price</label>
                    <input type="number" step="0.01" name="child_price" value="{{ old('child_price', $tour->child_price) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Group Discount</label>
                    <input type="number" step="0.01" name="group_discount" value="{{ old('group_discount', $tour->group_discount) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deposit (%)</label>
                    <input type="number" name="deposit_percent" value="{{ old('deposit_percent', $tour->deposit_percent) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="USD" {{ old('currency', $tour->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="TZS" {{ old('currency', $tour->currency) == 'TZS' ? 'selected' : '' }}>TZS</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="content-destination" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                    <select name="destination_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Destination</option>
                        @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}" {{ old('destination_id', $tour->destination_id) == $dest->id ? 'selected' : '' }}>{{ $dest->name }}, {{ $dest->country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Point</label>
                    <input type="text" name="meeting_point" value="{{ old('meeting_point', $tour->meeting_point) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
            </div>
        </div>

        <div id="content-duration" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Days</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $tour->duration_days) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Nights</label>
                    <input type="number" name="duration_nights" value="{{ old('duration_nights', $tour->duration_nights) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
            </div>
        </div>

        <div id="content-experience" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                    <select name="difficulty_level" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="easy" {{ old('difficulty_level', $tour->difficulty_level) == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="moderate" {{ old('difficulty_level', $tour->difficulty_level) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="hard" {{ old('difficulty_level', $tour->difficulty_level) == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tour Type</label>
                    <select name="tour_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="private" {{ old('tour_type', $tour->tour_type) == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="group" {{ old('tour_type', $tour->tour_type) == 'group' ? 'selected' : '' }}>Group</option>
                        <option value="luxury" {{ old('tour_type', $tour->tour_type) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        <option value="budget" {{ old('tour_type', $tour->tour_type) == 'budget' ? 'selected' : '' }}>Budget</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation Type</label>
                    <select name="accommodation_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="lodge" {{ old('accommodation_type', $tour->accommodation_type) == 'lodge' ? 'selected' : '' }}>Lodge</option>
                        <option value="camping" {{ old('accommodation_type', $tour->accommodation_type) == 'camping' ? 'selected' : '' }}>Camping</option>
                        <option value="hotel" {{ old('accommodation_type', $tour->accommodation_type) == 'hotel' ? 'selected' : '' }}>Hotel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transport Type</label>
                    <select name="transport_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="4x4" {{ old('transport_type', $tour->transport_type) == '4x4' ? 'selected' : '' }}>4x4 Jeep</option>
                        <option value="flight" {{ old('transport_type', $tour->transport_type) == 'flight' ? 'selected' : '' }}>Flight</option>
                        <option value="boat" {{ old('transport_type', $tour->transport_type) == 'boat' ? 'selected' : '' }}>Boat</option>
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
                <div x-show="mode === 'builder'" id="itinerary-container" class="space-y-4 mb-6">
                    @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                        @foreach($tour->itinerary as $dayNum => $day)
                            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-medium text-gray-800">Day {{ $dayNum }}</h4>
                                    <button type="button" class="remove-day text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Day Title</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][title]" value="{{ is_array($day['title'] ?? ($day['day_title'] ?? '')) ? implode(', ', $day['title'] ?? ($day['day_title'] ?? '')) : ($day['title'] ?? ($day['day_title'] ?? '')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="itinerary[{{ $dayNum }}][description]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ is_array($day['description'] ?? '') ? implode("\n", $day['description']) : ($day['description'] ?? '') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][accommodation]" value="{{ is_array($day['accommodation'] ?? '') ? implode(', ', $day['accommodation']) : ($day['accommodation'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Meals</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][meals]" value="{{ is_array($day['meals'] ?? '') ? implode(', ', $day['meals']) : ($day['meals'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Distance</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][distance]" value="{{ is_array($day['distance'] ?? '') ? implode(', ', $day['distance']) : ($day['distance'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Hiking Time</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][hiking_time]" value="{{ is_array($day['hiking_time'] ?? '') ? implode(', ', $day['hiking_time']) : ($day['hiking_time'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Habitat</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][habitat]" value="{{ is_array($day['habitat'] ?? '') ? implode(', ', $day['habitat']) : ($day['habitat'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Elevation</label>
                                        <input type="text" name="itinerary[{{ $dayNum }}][elevation]" value="{{ is_array($day['elevation'] ?? '') ? implode(', ', $day['elevation']) : ($day['elevation'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Activities</label>
                                        <textarea name="itinerary[{{ $dayNum }}][activities]" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ is_array($day['activities'] ?? '') ? implode(', ', $day['activities']) : ($day['activities'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Magic Code Mode UI --}}
                <div x-show="mode === 'code'" x-cloak class="space-y-4">
                    <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-2xl text-xs text-indigo-800">
                        <p class="font-black mb-3 uppercase tracking-widest flex items-center gap-2">
                            <span class="text-lg">🚀</span> Super Magic Mode (All-in-One)
                        </p>
                        <p class="mb-4 leading-relaxed">Unaweza kubandika kodi moja inayojumuisha <strong>Itinerary</strong>, <strong>Inclusions</strong>, <strong>Exclusions</strong>, na <strong>FAQs</strong> kwa pamoja. ChatGPT atakupa kila kitu kwa mpigo mmoja!</p>
                    </div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Raw JSON Package Data</label>
                    <textarea name="itinerary_raw" rows="20" class="w-full font-mono text-xs bg-gray-900 text-green-400 p-6 rounded-2xl focus:outline-none border-none shadow-2xl" placeholder="Paste the full JSON object here...">{{ json_encode(['itinerary' => $tour->itinerary, 'inclusions' => $tour->inclusions, 'exclusions' => $tour->exclusions, 'faqs' => $tour->faqs], JSON_PRETTY_PRINT) }}</textarea>
                </div>
            </div>
        </div>
        <div id="content-inclusions" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What's Included</label>
                    <div id="inclusions-container" class="space-y-2">
                        @if(is_array($tour->inclusions) && count($tour->inclusions) > 0)
                            @foreach($tour->inclusions as $inclusion)
                                <div class="flex gap-2">
                                    <input type="text" name="inclusions[]" value="{{ is_array($inclusion) ? implode(', ', $inclusion) : $inclusion }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                                </div>
                            @endforeach
                        @endif
                        <div class="flex gap-2">
                            <input type="text" name="inclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addInclusion()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What's Not Included</label>
                    <div id="exclusions-container" class="space-y-2">
                        @if(is_array($tour->exclusions) && count($tour->exclusions) > 0)
                            @foreach($tour->exclusions as $exclusion)
                                <div class="flex gap-2">
                                    <input type="text" name="exclusions[]" value="{{ is_array($exclusion) ? implode(', ', $exclusion) : $exclusion }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                                </div>
                            @endforeach
                        @endif
                        <div class="flex gap-2">
                            <input type="text" name="exclusions[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addExclusion()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What to Bring</label>
                    <div id="what-to-bring-container" class="space-y-2">
                        @if(is_array($tour->what_to_bring) && count($tour->what_to_bring) > 0)
                            @foreach($tour->what_to_bring as $item)
                                <div class="flex gap-2">
                                    <input type="text" name="what_to_bring[]" value="{{ is_array($item) ? implode(', ', $item) : $item }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                                </div>
                            @endforeach
                        @endif
                        <div class="flex gap-2">
                            <input type="text" name="what_to_bring[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addWhatToBring()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Good to Know</label>
                    <div id="good-to-know-container" class="space-y-2">
                        @if(is_array($tour->good_to_know) && count($tour->good_to_know) > 0)
                            @foreach($tour->good_to_know as $item)
                                <div class="flex gap-2">
                                    <input type="text" name="good_to_know[]" value="{{ is_array($item) ? implode(', ', $item) : $item }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                                </div>
                            @endforeach
                        @endif
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
                    @if(is_array($tour->faqs) && count($tour->faqs) > 0)
                        @foreach($tour->faqs as $index => $faq)
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-medium text-gray-800">FAQ #{{ $index + 1 }}</h4>
                                    <button type="button" onclick="this.closest('.p-4').remove()" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                                        <input type="text" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                                        <textarea name="faqs[{{ $index }}][answer]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ $faq['answer'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div id="content-media" class="tab-content hidden">
            <div class="mb-8 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Main Featured Image</h3>

                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-8 bg-white" id="featured-image-container">
                    @if($tour->featured_image)
                        <img id="featured-preview" src="{{ asset('storage/' . $tour->featured_image) }}" class="w-full max-w-md h-64 object-cover rounded-xl mb-4 shadow-md">
                        <div id="featured-placeholder" class="hidden text-center py-10">
                            <div class="text-4xl mb-2">📸</div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No Image Selected</p>
                        </div>
                    @else
                        <img id="featured-preview" src="" class="hidden w-full max-w-md h-64 object-cover rounded-xl mb-4 shadow-md">
                        <div id="featured-placeholder" class="text-center py-10">
                            <div class="text-4xl mb-2">📸</div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No Image Selected</p>
                        </div>
                    @endif

                    <input type="hidden" name="featured_image" id="featured_image_path" value="{{ $tour->featured_image }}">

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'featured_image_path', previewId: 'featured-preview'}}))"
                                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                            Change from Library
                        </button>
                        <p class="text-[10px] text-gray-400 self-center">OR</p>
                        <input type="file" name="featured_image_upload" class="text-xs file:bg-gray-100 file:border-none file:px-4 file:py-2 file:rounded-lg file:font-black file:uppercase">
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-4 italic">Recommended size: 1920x1080px. Picking from Library ensures WebP optimization.</p>
            </div>

            <div class="mb-8 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Gallery Images (Multiple)</h3>

                @if($tour->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
                        @foreach($tour->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" class="h-32 w-full object-cover rounded-lg shadow-sm border border-white">
                                <button type="button"
                                        onclick="if(confirm('Delete this gallery image?')) { document.getElementById('delete-img-{{ $image->id }}').submit(); }"
                                        class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload More Images</label>
                <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                <p class="text-xs text-gray-500 mt-2">You can select and upload multiple images at once.</p>
            </div>

            <div class="mb-6 p-6 border border-gray-100 rounded-2xl bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Video Content</h3>
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube/Vimeo URL</label>
                <input type="url" name="video_url" value="{{ old('video_url', $tour->video_url) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="https://www.youtube.com/watch?v=...">
            </div>
        </div>

        <div id="content-availability" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $tour->start_date) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $tour->end_date) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Slots</label>
                    <input type="number" name="available_slots" value="{{ old('available_slots', $tour->available_slots) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seasonal Tag</label>
                    <select name="seasonal_tag" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select</option>
                        <option value="high" {{ old('seasonal_tag', $tour->seasonal_tag) == 'high' ? 'selected' : '' }}>High Season</option>
                        <option value="low" {{ old('seasonal_tag', $tour->seasonal_tag) == 'low' ? 'selected' : '' }}>Low Season</option>
                        <option value="year-round" {{ old('seasonal_tag', $tour->seasonal_tag) == 'year-round' ? 'selected' : '' }}>Year-round</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Deadline (Days)</label>
                    <input type="number" name="booking_deadline_days" value="{{ old('booking_deadline_days', $tour->booking_deadline_days) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Availability Notes</label>
                    <textarea name="availability_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('availability_notes', $tour->availability_notes) }}</textarea>
                </div>
            </div>
        </div>

        <div id="content-marketing" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2 flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" {{ old('is_published', $tour->is_published) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Published</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" {{ old('is_featured', $tour->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Featured Package</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller', $tour->is_bestseller) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Bestseller</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_new" {{ old('is_new', $tour->is_new) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">New Package</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="limited_offer" {{ old('limited_offer', $tour->limited_offer) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Limited Offer</span>
                    </label>
                </div>
            </div>
        </div>

        <div id="content-seo" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $tour->meta_title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('meta_description', $tour->meta_description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <textarea name="meta_keywords" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('meta_keywords', $tour->meta_keywords) }}</textarea>
                </div>
            </div>
        </div>

        <div id="content-logistics" class="tab-content hidden">
            <div class="mb-8 bg-amber-50 p-6 rounded-2xl border border-amber-100">
                <h4 class="text-xs font-black text-amber-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Quick Select Arrival Point
                </h4>
                <div class="flex flex-wrap gap-3">
                    @php
                        $commonPoints = [
                            ['name' => 'Kilimanjaro Intl Airport (JRO)', 'lat' => '-3.4294', 'lng' => '37.0745'],
                            ['name' => 'Arusha Airport (ARK)', 'lat' => '-3.3675', 'lng' => '36.6333'],
                            ['name' => 'Moshi Town Center', 'lat' => '-3.3444', 'lng' => '37.3444'],
                            ['name' => 'Arusha City Center', 'lat' => '-3.3731', 'lng' => '36.6853'],
                        ];
                    @endphp
                    @foreach($commonPoints as $cp)
                        <button type="button"
                                onclick="setPickup('{{ $cp['name'] }}', '{{ $cp['lat'] }}', '{{ $cp['lng'] }}')"
                                class="px-4 py-2 bg-white border border-amber-200 rounded-xl text-[10px] font-bold text-amber-700 hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                            + {{ $cp['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mb-10 bg-indigo-50 p-8 rounded-[2rem] border border-indigo-100">
                <h4 class="text-xs font-black text-indigo-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Multiple Pickup Points (Show on Booking Page)
                </h4>
                <div id="pickup-locations-container" class="space-y-4">
                    @if(is_array($tour->pickup_locations) && count($tour->pickup_locations) > 0)
                        @foreach($tour->pickup_locations as $index => $pl)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded-2xl border border-indigo-100 shadow-sm">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Point Name</label>
                                <input type="text" name="pickup_locations[{{ $index }}][name]" value="{{ $pl['name'] ?? '' }}" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Latitude</label>
                                <input type="text" name="pickup_locations[{{ $index }}][lat]" value="{{ $pl['lat'] ?? '' }}" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
                            </div>
                            <div class="relative">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Longitude</label>
                                <div class="flex gap-2">
                                    <input type="text" name="pickup_locations[{{ $index }}][lng]" value="{{ $pl['lng'] ?? '' }}" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
                                    <button type="button" onclick="this.closest('.grid').remove()" class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addPickupPoint()" class="mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200">
                    + Add More Location
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pt-10 border-t border-gray-100">
                <div class="md:col-span-2 flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="pickup_included" {{ old('pickup_included', $tour->pickup_included) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Pickup Included</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="airport_pickup" {{ old('airport_pickup', $tour->airport_pickup) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Airport Pickup</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="transport_included" {{ old('transport_included', $tour->transport_included) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                        <span class="text-sm text-gray-700">Transport Included</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                    <input type="text" id="location-name" name="location_name" value="{{ old('location_name', $tour->location_name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $tour->latitude) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $tour->longitude) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="button" id="generate-map" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Generate Map Iframe</button>
                    <button type="button" id="find-coords" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Find Coordinates</button>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Map Iframe</label>
                    <textarea id="map-location" name="map_location" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('map_location', $tour->map_location) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <div id="map-preview" class="w-full h-64 border border-gray-300 rounded-lg overflow-hidden"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Guide</label>
                    <input type="text" name="assigned_guide" value="{{ old('assigned_guide', $tour->assigned_guide) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Age</label>
                    <input type="number" name="min_age" value="{{ old('min_age', $tour->min_age) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Age</label>
                    <input type="number" name="max_age" value="{{ old('max_age', $tour->max_age) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Languages Offered</label>
                    <div id="languages-container" class="space-y-2">
                        @if(is_array($tour->languages_offered) && count($tour->languages_offered) > 0)
                            @foreach($tour->languages_offered as $lang)
                                <div class="flex gap-2">
                                    <input type="text" name="languages_offered[]" value="{{ is_array($lang) ? implode(', ', $lang) : $lang }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">-</button>
                                </div>
                            @endforeach
                        @endif
                        <div class="flex gap-2">
                            <input type="text" name="languages_offered[]" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                            <button type="button" onclick="addLanguage()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Notes</label>
                    <textarea name="special_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('special_notes', $tour->special_notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Translation Tab --}}
        <div id="content-translate" class="tab-content hidden" x-data="{ lang: 'de' }">
            <div class="flex gap-2 mb-8 border-b border-gray-100 pb-4 overflow-x-auto">
                @foreach(['de' => '🇩🇪 German', 'fr' => '🇫🇷 French', 'es' => '🇪🇸 Spanish', 'it' => '🇮🇹 Italian', 'zh' => '🇨🇳 Chinese', 'nl' => '🇳🇱 Dutch'] as $code => $label)
                    <button type="button" @click="lang = '{{ $code }}'" :class="lang === '{{ $code }}' ? 'bg-gold-50 border-gold-500 text-gold-700' : 'bg-white text-gray-400 border-gray-100'" class="px-4 py-2 rounded-lg border font-bold text-xs transition-all">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @foreach(['de', 'fr', 'es', 'it', 'zh', 'nl'] as $locale)
            <div x-show="lang === '{{ $locale }}'" class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="translations[{{ $locale }}][title]" value="{{ $tour->translate('title', $locale) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Short Description ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][short_description]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">{{ $tour->translate('short_description', $locale) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Full Description ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][description]" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">{{ $tour->translate('description', $locale) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Footer Buttons -->
        <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('admin.tours.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Update Tour</button>
        </div>
    </form>

    {{-- Hidden Deletion Forms --}}
    @foreach($tour->images as $image)
        <form id="delete-img-{{ $image->id }}" action="{{ route('admin.tours.delete-image', $image) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</div>

<script>
let dayCount = {{ is_array($tour->itinerary) ? count($tour->itinerary) : 0 }};

function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

let plCount = {{ is_array($tour->pickup_locations) ? count($tour->pickup_locations) : 0 }};
function addPickupPoint() {
    plCount++;
    const html = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded-2xl border border-indigo-100 shadow-sm animate-fade-in">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Point Name</label>
                <input type="text" name="pickup_locations[${plCount}][name]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Latitude</label>
                <input type="text" name="pickup_locations[${plCount}][lat]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
            </div>
            <div class="relative">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Longitude</label>
                <div class="flex gap-2">
                    <input type="text" name="pickup_locations[${plCount}][lng]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
                    <button type="button" onclick="this.closest('.grid').remove()" class="text-red-500 hover:text-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('pickup-locations-container').insertAdjacentHTML('beforeend', html);
}

function setPickup(name, lat, lng) {
    document.getElementById('location-name').value = name;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    // Generate the map iframe immediately
    const iframe = `<iframe src="https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(lng)-0.01}%2C${parseFloat(lat)-0.01}%2C${parseFloat(lng)+0.01}%2C${parseFloat(lat)+0.01}&layer=mapnik&marker=${lat}%2C${lng}" width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" style="border: 1px solid black;"></iframe>`;
    document.getElementById('map-location').value = iframe;
    document.getElementById('map-preview').innerHTML = iframe;
}

const titleInput = document.getElementById('tour-title');
const slugInput = document.getElementById('tour-slug');

if (titleInput && slugInput) {
    // For edit page, we only auto-update if the slug matches the original title's slug
    const originalAutoSlug = slugify(titleInput.defaultValue || '');

    titleInput.addEventListener('input', function() {
        if (slugInput.dataset.manual === 'true') return;

        const currentSlug = slugInput.value;
        if (!currentSlug || currentSlug === originalAutoSlug || currentSlug === slugify(this.dataset.prevTitle || titleInput.defaultValue)) {
            slugInput.value = slugify(this.value);
            this.dataset.prevTitle = this.value;
        }
    });

    slugInput.addEventListener('input', function() {
        slugInput.dataset.manual = 'true';
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

let faqCount = {{ is_array($tour->faqs) ? count($tour->faqs) : 0 }};
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
