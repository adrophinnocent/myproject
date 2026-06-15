@extends('admin.layouts.app')

@section('title', 'AI Image Uploads')
@section('page-title', 'AI Image Uploads')

@section('content')
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex items-center gap-2 text-green-800">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.ai-images.create') }}" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Upload New Image
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($images as $image)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" class="w-full h-48 object-cover" loading="lazy">
        <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-1 truncate">{{ $image->title }}</h3>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 capitalize">{{ $image->category }}</span>
            </div>
            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $image->description }}</p>
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.ai-images.edit', $image) }}" class="text-[#D4AF37] hover:text-[#b8920d] text-sm font-medium">Edit</a>
                <form method="POST" action="{{ route('admin.ai-images.destroy', $image) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($images->hasPages())
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
    {{ $images->links() }}
</div>
@endif
@endsection
