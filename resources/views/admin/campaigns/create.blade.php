@extends('admin.layouts.app')

@section('title', 'New Advertisement')
@section('page-title', 'Create Advertisement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Import Section -->
        <div class="bg-amber-50 p-6 border-b border-amber-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-amber-900 text-sm">Import from Existing Tour</h3>
                    <p class="text-amber-700 text-xs mt-1">Select a tour to automatically fill the fields below.</p>
                </div>
                <div class="w-full md:w-72">
                    <select id="tour_importer" class="w-full border-amber-200 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500/20 bg-white">
                        <option value="">-- Choose a Tour to Import --</option>
                        @foreach($tours as $tour)
                            <option value="{{ $tour->id }}">{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaign-form" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Ad Title</label>
                    <input type="text" name="title" id="ad_title" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20" placeholder="e.g. 7-Day Serengeti Luxury Special">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Type / Category</label>
                    <select name="type" id="ad_type" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Price (USD)</label>
                <input type="number" name="price" id="ad_price" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20" placeholder="1200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Short Description (for Ad Preview)</label>
                <textarea name="description" id="ad_description" rows="3" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20" placeholder="A short, catchy text for social media shares..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Itinerary / Full Details (for Landing Page)</label>
                <textarea name="itinerary" id="ad_itinerary" rows="8" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20" placeholder="Day 1: Arrival... Day 2: Serengeti Game Drive..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Featured Image</label>
                    <input type="file" name="image" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                    <p class="text-[10px] text-gray-400 mt-1 italic">Note: Please re-upload the best image for this ad.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Status</label>
                    <select name="status" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                        <option value="draft">Draft</option>
                        <option value="published" selected>Published</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-10 py-4 rounded-xl font-bold shadow-lg transition-all hover:scale-105 active:scale-95">
                    Create Ad Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourImporter = document.getElementById('tour_importer');
    const urlParams = new URLSearchParams(window.location.search);
    const preselectId = urlParams.get('import_tour');

    function loadTourData(tourId) {
        fetch(`/admin/campaigns/tour-data/${tourId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('ad_title').value = data.title;
                document.getElementById('ad_description').value = data.description;
                document.getElementById('ad_itinerary').value = data.itinerary;
                document.getElementById('ad_price').value = data.price;

                // Select the correct category/type
                const typeSelect = document.getElementById('ad_type');
                for (let i = 0; i < typeSelect.options.length; i++) {
                    if (typeSelect.options[i].value === data.category) {
                        typeSelect.selectedIndex = i;
                        break;
                    }
                }
            });
    }

    if (preselectId) {
        tourImporter.value = preselectId;
        loadTourData(preselectId);
    }

    tourImporter.addEventListener('change', function() {
        if (this.value && confirm('This will clear current text and import data from the selected tour. Continue?')) {
            loadTourData(this.value);
        }
    });
});
</script>
@endsection
