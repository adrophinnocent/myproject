@forelse($media as $item)
<div class="group bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl transition-all duration-500 relative">
    <div class="aspect-square bg-gray-50 relative overflow-hidden">
        @if($item->type === 'image')
            <img src="{{ $item->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $item->alt }}">
        @elseif($item->type === 'video')
            <div class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-600">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        @endif

        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-safari-dark/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <button @click="deleteMedia({{ $item->id }})" class="w-10 h-10 rounded-xl bg-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all backdrop-blur-md flex items-center justify-center shadow-lg" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            <a href="{{ $item->url }}" target="_blank" class="w-10 h-10 rounded-xl bg-white/20 text-white hover:bg-white hover:text-safari-dark transition-all backdrop-blur-md flex items-center justify-center shadow-lg" title="View Full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>
    <div class="p-4">
        <p class="text-[10px] font-black text-gray-900 truncate tracking-tight uppercase" title="{{ $item->name }}">{{ $item->name }}</p>
        <div class="flex items-center justify-between mt-1">
            <span class="text-[8px] font-black text-amber-600 uppercase tracking-widest">{{ $item->mime_type }}</span>
            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">{{ $item->size_human }}</span>
        </div>
    </div>
</div>
@empty
<div class="col-span-full py-20 text-center neo-card">
    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No media files found</p>
</div>
@endforelse

@if($media->hasPages())
<div class="col-span-full pt-10">
    {{ $media->links() }}
</div>
@endif
