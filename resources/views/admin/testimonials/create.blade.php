@extends('admin.layouts.app')

@section('title', 'Add Testimonial')
@section('page-title', 'Add Testimonial')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
            <textarea name="content" rows="4" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('content') }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
            <select name="rating" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                @for($i = 1; $i <=5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
        </div>
        
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_published" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Published</span>
            </label>
        </div>
        
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded">
                <span class="text-sm text-gray-700">Featured</span>
            </label>
        </div>
        
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Save</button>
        </div>
    </form>
</div>
@endsection
