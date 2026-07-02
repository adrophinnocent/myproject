@extends('admin.layouts.app')

@section('title', 'Edit AI Fact')
@section('page-title', 'Edit Knowledge Entry')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.ai-knowledge.index') }}" class="text-amber-600 hover:text-amber-700 flex items-center gap-1 font-bold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.ai-knowledge.update', $fact) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Category</label>
                    <input type="text" name="category" value="{{ old('category', $fact->category) }}" placeholder="e.g. Serengeti, Kilimanjaro, Price" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Topic / Subject</label>
                    <input type="text" name="topic" value="{{ old('topic', $fact->topic) }}" required placeholder="e.g. Best time for migration" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">Content (The "Nondo")</label>
                <textarea name="content" rows="8" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">{{ old('content', $fact->content) }}</textarea>
            </div>

            <div class="flex items-center gap-8 pt-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $fact->is_active ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Active</span>
                </label>

                <div class="flex items-center gap-2">
                    <label class="text-sm font-bold text-gray-700">Sort Order:</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $fact->sort_order) }}" class="w-20 border border-gray-300 rounded-lg px-3 py-1 text-center">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-safari-dark hover:bg-black text-white font-black py-4 px-10 rounded-xl transition-all shadow-lg uppercase tracking-widest text-xs">
                    Update Fact
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
