@extends('admin.layouts.app')

@section('title', 'Add New FAQ')
@section('page-title', 'Add New FAQ')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
            <input type="text" name="question" value="{{ old('question') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
            <textarea name="answer" rows="5" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('answer') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="mb-6 flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" checked class="w-4 h-4 text-[#D4AF37] rounded border-gray-300">
                    <span class="text-sm text-gray-700">Active (Visible on Website)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-100 pt-6">
            <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Save FAQ</button>
        </div>
    </form>
</div>
@endsection
