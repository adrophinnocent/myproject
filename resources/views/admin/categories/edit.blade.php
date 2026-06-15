@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="cat-name" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" id="cat-slug" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from name</p>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description', $category->description) }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Update Category</button>
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

const nameInput = document.getElementById('cat-name');
const slugInput = document.getElementById('cat-slug');
const originalSlug = '{{ $category->slug }}';

if (nameInput && slugInput) {
    nameInput.addEventListener('input', function() {
        const currentSlug = slugInput.value;
        if (!currentSlug || currentSlug === originalSlug) {
            slugInput.value = slugify(this.value);
        }
    });
}
</script>
@endsection
