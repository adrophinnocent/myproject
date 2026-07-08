@extends('admin.layouts.app')

@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Campaign: ' . $campaign->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Import Section -->
        <div class="bg-amber-50 p-6 border-b border-amber-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-amber-900 text-sm">Replace Data from Tour</h3>
                    <p class="text-amber-700 text-xs mt-1">Select a tour to re-import and overwrite fields.</p>
                </div>
                <div class="w-full md:w-72">
                    <select id="tour_importer" class="w-full border-amber-200 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500/20 bg-white">
                        <option value="">-- Choose a Tour --</option>
                        @foreach($tours as $tour)
                            <option value="{{ $tour->id }}" {{ $campaign->tour_id == $tour->id ? 'selected' : '' }}>{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data" id="campaign-form" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tour_id" value="{{ $campaign->tour_id }}" id="ad_tour_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Ad Title</label>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Type / Category</label>
                    <input type="text" name="type" value="{{ old('type', $campaign->type) }}" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Price (USD)</label>
                <input type="number" name="price" value="{{ old('price', $campaign->price) }}" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Short Description (for Ad Preview)</label>
                <textarea name="description" rows="3" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Itinerary / Full Details (for Landing Page)</label>
                <textarea name="itinerary" rows="8" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">{{ old('itinerary', $campaign->itinerary) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Highlights (one per line)</label>
                <textarea name="highlights" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">{{ old('highlights', $campaign->highlights) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Inclusions</label>
                    <textarea name="inclusions" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">{{ old('inclusions', $campaign->inclusions) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Exclusions</label>
                    <textarea name="exclusions" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">{{ old('exclusions', $campaign->exclusions) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Change Image</label>
                    <input type="file" name="image" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                    @if($campaign->image)
                        <div class="mt-2">
                            <img src="{{ $campaign->image_url }}" class="h-20 rounded-lg">
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Status</label>
                    <select name="status" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20">
                        <option value="draft" {{ $campaign->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $campaign->status === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100 gap-4">
                <a href="{{ route('admin.campaigns.index') }}" class="px-6 py-4 rounded-xl font-bold text-gray-500 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-10 py-4 rounded-xl font-bold shadow-lg transition-all hover:scale-105 active:scale-95">
                    Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourImporter = document.getElementById('tour_importer');

    function loadTourData(tourId) {
        fetch(`/admin/campaigns/tour-data/${tourId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('ad_tour_id').value = tourId;

                // We use standard input finding by name for safety
                document.querySelector('input[name="title"]').value = data.title;
                document.querySelector('textarea[name="description"]').value = data.description;
                document.querySelector('textarea[name="itinerary"]').value = data.itinerary;
                document.querySelector('textarea[name="highlights"]').value = data.highlights;
                document.querySelector('textarea[name="inclusions"]').value = data.inclusions;
                document.querySelector('textarea[name="exclusions"]').value = data.exclusions;
                document.querySelector('input[name="price"]').value = data.price;
            });
    }

    tourImporter.addEventListener('change', function() {
        if (this.value && confirm('This will overwrite current fields with data from the selected tour. Continue?')) {
            loadTourData(this.value);
        }
    });
});
</script>
@endsection
