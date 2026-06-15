@extends('admin.layouts.app')

@section('title', 'Edit Image')
@section('page-title', 'Edit Image')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Image</h2>
                <img src="{{ $aiImage->image_url }}" alt="{{ $aiImage->alt_text }}" class="w-full rounded-lg shadow-sm mb-4" loading="lazy">
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600"><span class="font-medium">Category:</span> <span class="capitalize">{{ $aiImage->category }}</span></p>
                    <p class="text-gray-600"><span class="font-medium">Slug:</span> {{ $aiImage->slug }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Edit Details</h2>

            <form method="POST" action="{{ route('admin.ai-images.update', $aiImage) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title', $aiImage->title) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    @error('title')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('description', $aiImage->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alt Text</label>
                    <input type="text" name="alt_text" value="{{ old('alt_text', $aiImage->alt_text) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    @error('alt_text')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ old('tags', $aiImage->tags ? implode(', ', $aiImage->tags) : '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    @error('tags')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('admin.ai-images.index') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">Cancel</a>
                    <button type="submit" class="flex-1 bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-3 rounded-lg transition-colors">Update Image</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
