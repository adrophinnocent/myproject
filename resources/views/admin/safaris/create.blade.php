@extends('admin.layouts.app')

@section('title', 'Create Safari')
@section('page-title', 'Create Safari')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="font-medium text-red-800">Please fix the following errors:</h4>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.safaris.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Simplified creation for now, can be expanded to match Tour exactly -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Safari Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (USD)</label>
                <input type="number" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                <input type="number" name="duration_days" value="{{ old('duration_days') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
            <input type="file" name="featured_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_published" checked class="w-4 h-4 text-[#D4AF37] rounded">
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
