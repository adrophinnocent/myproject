@extends('admin.layouts.app')

@section('title', 'Testimonials')
@section('page-title', 'Testimonials')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.testimonials.create') }}" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Testimonial
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Published</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach(\App\Models\Testimonial::latest()->get() as $testimonial)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $testimonial->name }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $testimonial->location }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-[#D4AF37]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $testimonial->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $testimonial->is_published ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-[#D4AF37] hover:text-[#b8920d] mr-3">Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
