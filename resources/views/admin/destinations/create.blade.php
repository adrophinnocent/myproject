@extends('admin.layouts.app')

@section('title', 'Create Destination')
@section('page-title', 'Create Destination')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="dest-name" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" id="dest-slug" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from name</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" value="{{ old('country') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Featured Image</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-8 bg-gray-50 mb-4">
                <img id="dest-preview" src="" class="hidden w-full max-w-xs h-32 object-cover rounded-xl mb-4 shadow-md">
                <div id="dest-placeholder" class="text-center py-4">
                    <div class="text-4xl mb-2">📸</div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No Image Selected</p>
                </div>

                <input type="hidden" name="featured_image_path" id="featured_image_path">

                <div class="flex gap-3">
                    <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'featured_image_path', previewId: 'dest-preview'}}))"
                            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-lg">
                        Choose from Library
                    </button>
                    <p class="text-[10px] text-gray-400 self-center">OR</p>
                    <input type="file" name="featured_image" accept="image/*" class="text-xs file:bg-white file:border file:border-gray-200 file:px-4 file:py-1.5 file:rounded-lg file:font-black file:uppercase">
                </div>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Active</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Featured</span>
            </label>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.destinations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Create Destination</button>
        </div>
    </form>
</div>

<script>
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

const nameInput = document.getElementById('dest-name');
const slugInput = document.getElementById('dest-slug');

if (nameInput && slugInput) {
    nameInput.addEventListener('input', function() {
        const currentSlug = slugInput.value;
        if (!currentSlug) {
            slugInput.value = slugify(this.value);
        }
    });
}
</script>
@endsection
