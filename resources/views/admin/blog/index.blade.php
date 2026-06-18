@extends('admin.layouts.app')

@section('title', 'Safari Stories')
@section('page-title', 'Safari Journal / Blog')

@section('content')
<div class="space-y-8">
    {{-- Header CTA --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tighter">Your Published Stories</h3>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manage articles and travel insights</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="bg-safari-dark hover:bg-black text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-black/10 flex items-center gap-3">
            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Write New Story
        </a>
    </div>

    {{-- Blog List --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Story Details</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Category</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Published</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($posts as $post)
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 rounded-xl bg-gray-100 overflow-hidden border border-gray-100 shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                <img src="{{ $post->featured_image_url }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-gray-900 line-clamp-1 leading-tight">{{ $post->translate('title') }}</div>
                                <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mt-1">/{{ $post->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-100 px-3 py-1 rounded-full">
                            {{ $post->category->name ?? 'Safari' }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        @if($post->is_published)
                            <div class="flex items-center gap-1.5 text-green-600 font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Live
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 text-gray-400 font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                Draft
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[11px] font-bold text-gray-500">{{ $post->published_at ? $post->published_at->format('d M, Y') : 'N/A' }}</div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-gold-600 hover:border-gold-500 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Archive this story?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 bg-white border border-red-50 rounded-xl flex items-center justify-center text-red-200 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="text-gray-400 font-bold uppercase tracking-widest text-xs">No stories written yet</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
