@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Guest Story')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-10">
        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Guest Name</label>
                    <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Safari Type / Location</label>
                    <input type="text" name="tour_name" value="{{ old('tour_name', $testimonial->tour_name ?? $testimonial->location) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all" placeholder="e.g. Serengeti Honeymoon">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Guest Photo</label>
                    <div class="flex items-center gap-4">
                        @if($testimonial->image)
                            <img src="{{ asset('storage/'.$testimonial->image) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gold-500">
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-gold-50 file:text-gold-600 transition-all">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Rating (1-5)</label>
                    <select name="rating" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 outline-none">
                        @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                            {{ str_repeat('⭐', $i) }} ({{ $i == 5 ? 'Excellent' : ($i == 4 ? 'Great' : ($i == 3 ? 'Good' : ($i == 2 ? 'Fair' : 'Poor'))) }})
                        </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="space-y-2 mb-8">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Guest Feedback</label>
                <textarea name="content" rows="6" required
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all italic leading-relaxed"
                          placeholder="What did they love about their safari?">{{ old('content', $testimonial->content) }}</textarea>
            </div>

            <div class="flex items-center gap-6 mb-10 p-6 bg-gold-50/50 rounded-2xl border border-gold-100">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gold-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
                    <span class="ms-3 text-sm font-black text-safari-dark uppercase tracking-widest">Make Live on Website</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-safari-dark hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-2xl shadow-black/20 uppercase tracking-widest text-sm">
                    Update Guest Story
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="px-10 py-5 bg-white border border-gray-200 text-gray-400 hover:text-gray-600 rounded-2xl font-black uppercase tracking-widest text-sm transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
