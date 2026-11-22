@extends('admin.layouts.app')

@section('title', 'Add New Q&A / FAQ')
@section('page-title', 'Add New Q&A Item')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-stone-200 p-8 space-y-6">
    <div>
        <h2 class="font-serif font-bold text-xl text-stone-900">Publish Q&A to Tour Packages, Destinations & Search Engines</h2>
        <p class="text-xs text-stone-500 mt-1">This Q&A will automatically render on linked tour package pages, destination guides, Google Rich Search Snippets (Schema.org), and AI Search systems.</p>
    </div>

    <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question') }}" required placeholder="e.g. Is the 8-Day Machame Route suitable for beginners?" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Answer (Detailed & Helpful Response) *</label>
            <textarea name="answer" rows="5" required placeholder="Write a thorough response explaining trek difficulty, preparation, success rates, and guide support..." class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-sm leading-relaxed focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">{{ old('answer') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Link to Tour Package (Optional)</label>
                <select name="tour_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
                    <option value="">General (All Tours / Main FAQ)</option>
                    @foreach($tours as $t)
                        <option value="{{ $t->id }}" {{ old('tour_id') == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Link to Destination (Optional)</label>
                <select name="destination_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
                    <option value="">None (All Destinations)</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->id }}" {{ old('destination_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Primary Keyword Badge</label>
                <input type="text" name="primary_keyword" value="{{ old('primary_keyword') }}" placeholder="e.g. Kilimanjaro Trekking, Serengeti Safari" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-stone-100">
            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_verified_traveler" value="1" checked class="w-4 h-4 text-amber-600 rounded border-stone-300">
                    <span class="text-xs font-bold text-stone-800">✨ Verified Traveler Answer Badge</span>
                </label>
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-amber-600 rounded border-stone-300">
                    <span class="text-xs font-bold text-stone-800">Publish Immediately</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-stone-100">
            <a href="{{ route('admin.faqs.index') }}" class="px-5 py-3 border border-stone-300 rounded-xl text-stone-700 text-xs font-bold hover:bg-stone-50 transition-colors">Cancel</a>
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-xs font-black uppercase tracking-wider shadow-md">Publish Q&A Item</button>
        </div>
    </form>
</div>
@endsection
