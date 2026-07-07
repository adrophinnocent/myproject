@extends('admin.layouts.app')

@section('title', 'Create Album')
@section('page-title', 'Create New Album')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-6" x-data="{ isCustom: false }">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Album Category</label>
            <select id="album-selector" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 mb-4"
                    onchange="handleSelection(this)">
                <option value="">-- Choose an Album Name --</option>
                <optgroup label="Adventure Types">
                    <option value="Luxury Safari Highlights">Luxury Safari Highlights</option>
                    <option value="Kilimanjaro Trekking">Kilimanjaro Trekking</option>
                    <option value="Zanzibar Beach Paradise">Zanzibar Beach Paradise</option>
                    <option value="Family Safari Memories">Family Safari Memories</option>
                    <option value="Honeymoon & Romance">Honeymoon & Romance</option>
                    <option value="Day Trips Adventure">Day Trips Adventure</option>
                </optgroup>
                <optgroup label="Destinations">
                    <option value="Serengeti National Park">Serengeti National Park</option>
                    <option value="Ngorongoro Crater Wonders">Ngorongoro Crater Wonders</option>
                    <option value="Tarangire Wildlife">Tarangire Wildlife</option>
                    <option value="Mount Kilimanjaro Views">Mount Kilimanjaro Views</option>
                </optgroup>
                <option value="CUSTOM">-- Create My Own Name --</option>
            </select>

            <div id="custom-name-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Album Name</label>
                <input type="text" id="album-name" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500" placeholder="Enter custom name">
            </div>
            <!-- Hidden input to store the final name if dropdown is used -->
            <input type="hidden" id="final-name" name="name" value="{{ old('name') }}">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
            <input type="text" id="album-slug" name="slug" value="{{ old('slug') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500" placeholder="serengeti-safari-highlights">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">{{ old('description') }}</textarea>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
        </div>
        <div class="mb-6">
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_published" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                <span class="text-sm text-gray-700">Publish immediately</span>
            </label>
        </div>
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">Create Album</button>
        </div>
    </form>
</div>
@endsection

<script>
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

function handleSelection(select) {
    const customContainer = document.getElementById('custom-name-container');
    const albumNameInput = document.getElementById('album-name');
    const slugInput = document.getElementById('album-slug');
    const finalName = document.getElementById('final-name');

    if (select.value === 'CUSTOM') {
        customContainer.style.display = 'block';
        albumNameInput.required = true;
        albumNameInput.value = '';
        slugInput.value = '';
        finalName.name = ""; // Disable hidden input so text input takes over
    } else if (select.value !== "") {
        customContainer.style.display = 'none';
        albumNameInput.required = false;
        albumNameInput.value = select.value;
        finalName.value = select.value;
        finalName.name = "name"; // Ensure hidden input is sent
        slugInput.value = slugify(select.value);
    } else {
        customContainer.style.display = 'none';
        albumNameInput.value = '';
        slugInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('album-name');
    const slugInput = document.getElementById('album-slug');
    const finalName = document.getElementById('final-name');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = slugify(this.value);
            finalName.value = this.value;
        });
    }
});
</script>
