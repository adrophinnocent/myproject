@extends('admin.layouts.app')

@section('title', 'Edit Slider')
@section('page-title', 'Edit Slider')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Target Page</label>
            <select name="page" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                <option value="home" {{ old('page', $slider->page) == 'home' ? 'selected' : '' }}>Home Page (Main Slider)</option>
                <option value="contact" {{ old('page', $slider->page) == 'contact' ? 'selected' : '' }}>Contact Page (Banner)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Slider Image</label>
            @if($slider->image)
                <div class="mb-3 relative group">
                    <img src="{{ $slider->image_url }}" class="w-full h-40 object-cover rounded-lg border border-gray-200 shadow-sm">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg text-white text-xs">Current Image</div>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#D4AF37]/10 file:text-[#8b7355] hover:file:bg-[#D4AF37]/20">
            <p class="text-[10px] text-gray-400 mt-1">Leave empty to keep existing image</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                <input type="text" name="cta_text" value="{{ old('cta_text', $slider->cta_text) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button URL</label>
                <input type="text" name="cta_url" value="{{ old('cta_url', $slider->cta_url) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $slider->order) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="mb-6 flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="active" {{ old('active', $slider->active) ? 'checked' : '' }} class="w-4 h-4 text-[#D4AF37] rounded border-gray-300">
                    <span class="text-sm text-gray-700">Active (Visible on Homepage)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-100 pt-6">
            <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Update Slider</button>
        </div>
    </form>
</div>
@endsection
