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
            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
            <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#D4AF37]/10 file:text-[#8b7355] hover:file:bg-[#D4AF37]/20">
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
