@extends('admin.layouts.app')

@section('title', 'Manage FAQs')
@section('page-title', 'Manage FAQs')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.faqs.create') }}" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add New FAQ
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Question</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($faqs as $faq)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-600">{{ $faq->order }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $faq->question }}</div>
                    <div class="text-xs text-gray-500 truncate max-w-md">{{ Str::limit($faq->answer, 100) }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $faq->is_active ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $faq->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-[#D4AF37] hover:text-[#b8920d] mr-3">Edit</a>
                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-500">No FAQs found. Add your first FAQ!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
