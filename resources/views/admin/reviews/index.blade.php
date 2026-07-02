@extends('admin.layouts.app')

@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tour</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($reviews as $review)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $review->name }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $review->tour?->title ?? '—' }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1">
                        @for($i=1; $i<=5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-[#D4AF37]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline-block mr-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-[#D4AF37] hover:text-[#b8920d]">
                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
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
        {{ $reviews->links() }}
    </div>
</div>
@endsection
