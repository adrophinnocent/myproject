@extends('admin.layouts.app')

@section('title', 'Tours')
@section('page-title', 'Tours')

@section('content')
<div class="space-y-8">
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div class="neo-card p-6 border-l-4 border-amber-500">
            <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Total Tours</div>
            <div class="text-3xl font-black text-gray-800">{{ $tours->total() }}</div>
        </div>
        @foreach($categories->take(4) as $cat)
        <div class="neo-card p-6">
            <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">{{ $cat->name }}</div>
            <div class="text-3xl font-black text-amber-600">{{ $cat->tours_count }}</div>
        </div>
        @endforeach
    </div>

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.tours.create') }}" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Tour
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
        <table class="w-full min-w-[800px]">
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
                        <div class="text-[10px] text-gray-400 font-mono">{{ $tour->slug }}</div>
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
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.campaigns.create') }}?import_tour={{ $tour->id }}" class="text-amber-600 hover:text-amber-800 font-bold text-[10px] uppercase bg-amber-50 px-2 py-1 rounded border border-amber-200" title="Create Advertisement">Ad Campaign</a>
                            <a href="{{ route('tours.show', ['type' => 'tour', 'slug' => $tour->slug]) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View</a>
                            <form method="POST" action="{{ route('admin.tours.toggle-publish', $tour) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="{{ $tour->is_published ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                                    {{ $tour->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.tours.edit', $tour) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                            <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tours->links() }}
        </div>
    </div>
</div>
@endsection
