@extends('admin.layouts.app')

@section('title', 'Gallery')
@section('page-title', 'Gallery Albums')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-600">Manage your photo albums and upload new content</p>
    <a href="{{ route('admin.gallery.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Album
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($albums as $album)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="h-48 bg-gray-100 relative">
                @if ($album->cover_image)
                    <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 text-xs rounded-full {{ $album->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $album->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900">{{ $album->name }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ $album->images_count }} Photos</p>
                @if ($album->description)
                    <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $album->description }}</p>
                @endif
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.gallery.edit', $album) }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this album?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
