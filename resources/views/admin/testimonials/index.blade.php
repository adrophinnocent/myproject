@extends('admin.layouts.app')

@section('title', 'Guest Stories')
@section('page-title', 'What Travelers Say')

@section('content')
<div class="space-y-8">
    {{-- Header CTA --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tighter">Guest Testimonials</h3>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manage feedback from your travelers</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="bg-safari-dark hover:bg-black text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-black/10 flex items-center gap-3">
            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Guest Story
        </a>
    </div>

    {{-- Testimonials List --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Guest Details</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Feedback Snippet</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Rating</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach(\App\Models\Testimonial::latest()->get() as $testimonial)
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/'.$testimonial->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center font-black text-gray-400 text-sm bg-gray-50">
                                        {{ substr($testimonial->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-gray-900 leading-tight">{{ $testimonial->name }}</div>
                                <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mt-1">{{ $testimonial->tour_name ?? $testimonial->location }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <p class="text-xs text-gray-500 italic line-clamp-1 max-w-xs">"{{ $testimonial->content }}"</p>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex text-gold-500 gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $testimonial->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @if($testimonial->is_published)
                            <div class="flex items-center gap-1.5 text-green-600 font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Live
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 text-gray-400 font-black text-[10px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                Hidden
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-gold-600 hover:border-gold-500 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete this story?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 bg-white border border-red-50 rounded-xl flex items-center justify-center text-red-200 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
