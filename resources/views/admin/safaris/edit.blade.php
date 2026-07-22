@extends('admin.layouts.app')

@section('title', 'Edit Safari')
@section('page-title', 'Edit Safari')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.safaris.update', $safari) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Safari Title</label>
                <input type="text" name="title" value="{{ old('title', $safari->title) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $safari->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (USD)</label>
                <input type="number" name="price" value="{{ old('price', $safari->price) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                <input type="number" name="duration_days" value="{{ old('duration_days', $safari->duration_days) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
            @if($safari->featured_image)
                <div class="mb-2">
                    <img src="{{ $safari->featured_image_url }}" class="h-20 w-32 object-cover rounded-lg border">
                </div>
            @endif
            <input type="file" name="featured_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_published" {{ $safari->is_published ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Published</span>
            </label>
        </div>

        <!-- Footer Buttons -->
        <div class="pt-10 border-t border-gray-200 flex flex-col items-center gap-4">
            <button type="submit" class="w-full md:w-96 bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-black py-4 rounded-2xl transition-all shadow-xl shadow-gold-500/20 uppercase tracking-[0.2em] text-xs">
                Submit
            </button>
            <a href="{{ route('admin.safaris.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all">
                Cancel & Return
            </a>
        </div>
    </form>
</div>
@endsection
