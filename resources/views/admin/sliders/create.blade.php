@extends('admin.layouts.app')

@section('title', 'Add New Slider')
@section('page-title', 'Add New Slider')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="e.g. Discover Tanzania's Wild Beauty">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="e.g. Luxury Safaris & Kilimanjaro Treks">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Target Page</label>
            <select name="page" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                <option value="home" {{ old('page') == 'home' ? 'selected' : '' }}>Home Page (Main Slider)</option>
                <option value="contact" {{ old('page') == 'contact' ? 'selected' : '' }}>Contact Page (Banner)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Slider Image (Recommended: 1920x1080px)</label>
            <input type="file" name="image" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#D4AF37]/10 file:text-[#8b7355] hover:file:bg-[#D4AF37]/20">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                <input type="text" name="cta_text" value="{{ old('cta_text', 'Explore Our Safaris') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button URL</label>
                <input type="text" name="cta_url" value="{{ old('cta_url', '/tours') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="mb-6 flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="active" checked class="w-4 h-4 text-[#D4AF37] rounded border-gray-300">
                    <span class="text-sm text-gray-700">Active (Visible on Homepage)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-100 pt-6">
            <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Save Slider</button>
        </div>
    </form>
</div>
@endsection
