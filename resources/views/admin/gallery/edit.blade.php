@extends('admin.layouts.app')

@section('title', 'Edit Album')
@section('page-title', 'Edit Album: ' . $album->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Album Details</h3>
        <form method="POST" action="{{ route('admin.gallery.update', $album) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Album Name</label>
                <input type="text" name="name" value="{{ old('name', $album->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $album->slug) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">{{ old('description', $album->description) }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                @if ($album->cover_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->name }}" class="w-full h-32 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_published" {{ old('is_published', $album->is_published) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                    <span class="text-sm text-gray-700">Published</span>
                </label>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">Update Album</button>
            </div>
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Add Photos</h3>
        <form method="POST" action="{{ route('admin.gallery.add-image', $album) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                <input type="file" name="image" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Caption (optional)</label>
                <input type="text" name="caption" value="{{ old('caption') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors">Upload Photo</button>
            </div>
        </form>
        
        <h3 class="text-lg font-semibold text-gray-900 mt-8 mb-4">Album Photos ({{ $album->images->count() }})</h3>
        <div class="grid grid-cols-3 gap-4">
            @foreach ($album->images as $image)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption }}" class="w-full h-24 object-cover rounded-lg">
                    <form method="POST" action="{{ route('admin.gallery.remove-image', $image) }}" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg" onsubmit="return confirm('Delete this photo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
