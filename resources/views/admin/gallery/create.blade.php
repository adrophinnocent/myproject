@extends('admin.layouts.app')

@section('title', 'Create Album')
@section('page-title', 'Create New Album')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-6" x-data="{ isCustom: false }">
            <label class="block text-sm font-medium text-gray-700 mb-2">Album Name</label>
            <input type="text" id="album-name" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500" placeholder="Enter album name (e.g. Serengeti Wildlife)">
            <p class="mt-2 text-xs text-gray-500 italic">Quick suggestions:
                <button type="button" onclick="setQuickName('Luxury Safari Highlights')" class="text-amber-600 hover:underline">Luxury Safari</button>,
                <button type="button" onclick="setQuickName('Kilimanjaro Trekking')" class="text-amber-600 hover:underline">Kilimanjaro</button>,
                <button type="button" onclick="setQuickName('Zanzibar Paradise')" class="text-amber-600 hover:underline">Zanzibar</button>
            </p>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
            <input type="text" id="album-slug" name="slug" value="{{ old('slug') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500" placeholder="serengeti-wildlife">
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

function setQuickName(val) {
    const nameInput = document.getElementById('album-name');
    const slugInput = document.getElementById('album-slug');
    nameInput.value = val;
    slugInput.value = slugify(val);
}

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('album-name');
    const slugInput = document.getElementById('album-slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = slugify(this.value);
        });
    }
});
</script>
