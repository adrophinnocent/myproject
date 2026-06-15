@extends('admin.layouts.app')

@section('title', 'Create Blog Post')
@section('page-title', 'Create New Post')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Post Title</label>
                <input type="text" id="blog-title" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                <input type="text" id="blog-slug" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate from title</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Featured Image</label>
                    <input type="file" name="featured_image" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Excerpt (Short Summary)</label>
                <textarea name="excerpt" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('excerpt') }}</textarea>
                @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                <textarea name="content" rows="12" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="w-5 h-5 text-[#D4AF37] rounded focus:ring-[#D4AF37]">
                    <span class="text-sm font-semibold text-gray-700">Publish immediately</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-bold px-8 py-3 rounded-xl transition-colors shadow-lg">
                    Create Post
                </button>
                <a href="{{ route('admin.blog.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-8 py-3 rounded-xl transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
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

const titleInput = document.getElementById('blog-title');
const slugInput = document.getElementById('blog-slug');

if (titleInput && slugInput) {
    titleInput.addEventListener('input', function() {
        const currentSlug = slugInput.value;
        if (!currentSlug) {
            slugInput.value = slugify(this.value);
        }
    });
}
</script>
@endsection
