@extends('admin.layouts.app')

@section('title', 'Edit Q&A')
@section('page-title', 'Edit Q&A Item')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-stone-200 p-8 space-y-6">
    <div>
        <h2 class="font-serif font-bold text-xl text-stone-900">Edit Q&A Item</h2>
        <p class="text-xs text-stone-500 mt-1">Updates will instantly reflect on linked tour packages, destinations, Google Rich Snippets, and AI Search indexes.</p>
    </div>

    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Answer *</label>
            <textarea name="answer" rows="5" required class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-sm leading-relaxed focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Link to Tour Package</label>
                <select name="tour_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
                    <option value="">General (All Tours / Main FAQ)</option>
                    @foreach($tours as $t)
                        <option value="{{ $t->id }}" {{ old('tour_id', $faq->tour_id) == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Link to Destination</label>
                <select name="destination_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
                    <option value="">None (All Destinations)</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->id }}" {{ old('destination_id', $faq->destination_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Primary Keyword Badge</label>
                <input type="text" name="primary_keyword" value="{{ old('primary_keyword', $faq->primary_keyword) }}" placeholder="e.g. Kilimanjaro Trekking" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-stone-100">
            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $faq->order) }}" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_verified_traveler" value="1" {{ old('is_verified_traveler', $faq->is_verified_traveler) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded border-stone-300">
                    <span class="text-xs font-bold text-stone-800">✨ Verified Traveler Answer Badge</span>
                </label>
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded border-stone-300">
                    <span class="text-xs font-bold text-stone-800">Publish Immediately</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-stone-100">
            <a href="{{ route('admin.faqs.index') }}" class="px-5 py-3 border border-stone-300 rounded-xl text-stone-700 text-xs font-bold hover:bg-stone-50 transition-colors">Cancel</a>
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-xs font-black uppercase tracking-wider shadow-md">Update Q&A Item</button>
        </div>
    </form>
</div>
@endsection
