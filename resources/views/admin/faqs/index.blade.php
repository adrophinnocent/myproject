@extends('admin.layouts.app')

@section('title', 'FAQs Management')
@section('page-title', 'FAQs Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-stone-900">Frequently Asked Questions</h2>
            <p class="text-xs text-stone-500 mt-0.5">Manage global questions and answers displayed on the website.</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-gold px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-wider shadow-md inline-flex items-center gap-2">
            <span>+ Add New FAQ</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
            <span>✓ {{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-500 uppercase font-black text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4"># Order</th>
                        <th class="px-6 py-4">Question & Answer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($faqs as $faq)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-stone-900">
                            {{ $faq->order }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-stone-900 text-sm mb-1">{{ $faq->question }}</div>
                            <div class="text-stone-500 line-clamp-2 max-w-xl">{!! strip_tags($faq->answer) !!}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $faq->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $faq->is_active ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold rounded-lg transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg font-bold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center text-stone-400 italic">No FAQs created yet. Click "+ Add New FAQ" to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
