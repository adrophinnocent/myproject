@extends('admin.layouts.app')

@section('title', 'Add New FAQ')
@section('page-title', 'Add New FAQ Item')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-stone-200 p-8 space-y-6">
    <div>
        <h2 class="font-serif font-bold text-xl text-stone-900">Add New FAQ</h2>
        <p class="text-xs text-stone-500 mt-1">This question and answer will appear on the public FAQs page and homepage.</p>
    </div>

    <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question') }}" required placeholder="e.g. What is the best time for a safari in Tanzania?" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Answer *</label>
            <textarea name="answer" rows="5" required placeholder="Write a clear and helpful response..." class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-sm leading-relaxed focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">{{ old('answer') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-stone-100">
            <div>
                <label class="block text-xs font-black text-stone-700 uppercase tracking-wider mb-2">Display Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
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
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-xs font-black uppercase tracking-wider shadow-md">Publish FAQ</button>
        </div>
    </form>
</div>
@endsection
