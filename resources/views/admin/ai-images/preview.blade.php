@extends('admin.layouts.app')

@section('title', 'Preview Uploaded Image')
@section('page-title', 'Preview & Confirm')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Image Preview Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Image Preview</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Original Image</h3>
                    <div class="rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $original_path) }}" alt="Original" class="w-full h-48 object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Optimized WebP</h3>
                    <div class="rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $optimized_path) }}" alt="Optimized" class="w-full h-48 object-cover">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Category</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm capitalize">{{ $category }}</span>
            </div>
        </div>

        <!-- AI Content Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">AI Generated Content</h2>

            <form method="POST" action="{{ route('admin.ai-images.confirm') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title', $ai_content['title']) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description', $ai_content['description']) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alt Text</label>
                    <input type="text" name="alt_text" value="{{ old('alt_text', $ai_content['alt_text']) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ old('tags', implode(', ', $ai_content['tags'])) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('admin.ai-images.create') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">Upload Another</a>
                    <button type="submit" class="flex-1 bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-3 rounded-lg transition-colors">Save Image</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
