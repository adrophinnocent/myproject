@extends('admin.layouts.app')

@section('title', 'Tours')
@section('page-title', 'Tours')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.tours.create') }}" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add New Tour
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Destination</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($tours as $tour)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $tour->title }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $tour->category?->name ?? '—' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $tour->destination?->name ?? '—' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-semibold text-gray-900">${{ number_format($tour->price, 2) }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $tour->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $tour->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <form method="POST" action="{{ route('admin.tours.toggle-publish', $tour) }}" class="inline-block mr-3">
                        @csrf
                        <button type="submit" class="{{ $tour->is_published ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                            {{ $tour->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.tours.edit', $tour) }}" class="text-[#D4AF37] hover:text-[#b8920d] mr-3">Edit</a>
                    <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $tours->links() }}
    </div>
</div>
@endsection
