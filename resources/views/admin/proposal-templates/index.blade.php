@extends('admin.layouts.app')

@section('title', 'Proposal Templates')
@section('page-title', 'Proposal Templates')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Proposal Templates Management</h2>
        <p class="text-gray-500 font-medium text-xs mt-1">Reusable safari itinerary blueprints used internally by admin to quickly generate client proposals.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.proposals.create') }}" class="px-4 py-2.5 neo-btn text-xs font-bold text-gray-700 uppercase flex items-center gap-2">
            Back to Proposals
        </a>
        <a href="{{ route('admin.proposal-templates.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-black text-xs uppercase shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Create New Template
        </a>
    </div>
</div>

{{-- Filter & Search Bar --}}
<div class="neo-card p-4 mb-6">
    <form action="{{ route('admin.proposal-templates.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
        <div class="flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search templates by title, style or level..."
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
        </div>
        <div class="w-full md:w-48">
            <select name="status" @change="$form.submit()" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
                <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Active Templates</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived Templates</option>
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
            </select>
        </div>
        <button type="submit" class="w-full md:w-auto px-6 py-2.5 neo-btn text-xs font-bold text-gray-700 uppercase">
            Filter
        </button>
    </form>
</div>

<div class="neo-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-100/80 border-b border-gray-200 text-gray-600 uppercase font-black tracking-wider">
                    <th class="p-4">Template Title</th>
                    <th class="p-4">Duration</th>
                    <th class="p-4">Style & Level</th>
                    <th class="p-4">Route Summary</th>
                    <th class="p-4">Std Selling Price</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 font-medium text-gray-800">
                @forelse($templates as $template)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-4">
                        <div class="font-black text-gray-900 text-sm">{{ $template->title }}</div>
                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $template->subtitle ?: 'Reusable Blueprint' }}</div>
                    </td>
                    <td class="p-4">
                        <span class="font-bold text-slate-800">{{ $template->duration_days }} Days / {{ $template->duration_nights }} Nights</span>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-amber-700">{{ $template->safari_style ?: 'Private Safari' }}</div>
                        <div class="text-[10px] uppercase font-bold text-gray-400 mt-0.5">{{ $template->accommodation_level ?: 'Comfort' }}</div>
                    </td>
                    <td class="p-4 max-w-xs truncate text-gray-600">
                        {{ $template->route_summary ?: ($template->start_location . ' → ' . $template->end_location) }}
                    </td>
                    <td class="p-4 font-black text-gray-900 text-sm">
                        ${{ number_format($template->default_total_price, 2) }}
                    </td>
                    <td class="p-4">
                        @if($template->status === 'archived')
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 border border-gray-300 rounded-full font-bold text-[10px] uppercase">Archived</span>
                        @else
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full font-bold text-[10px] uppercase">Active</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.proposal-templates.use', $template) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-black text-[10px] uppercase tracking-wider shadow-sm transition-all" title="Use as client proposal">
                                Use Template
                            </a>
                            <a href="{{ route('admin.proposal-templates.show', $template) }}" class="px-2.5 py-1.5 neo-btn text-gray-700 font-bold text-[10px] uppercase" title="Preview Template">
                                Preview
                            </a>
                            <a href="{{ route('admin.proposal-templates.edit', $template) }}" class="px-2.5 py-1.5 neo-btn text-indigo-700 font-bold text-[10px] uppercase" title="Edit Template">
                                Edit
                            </a>

                            {{-- Duplicate --}}
                            <form action="{{ route('admin.proposal-templates.duplicate', $template) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 neo-btn text-blue-700 font-bold text-[10px] uppercase" title="Duplicate Template">
                                    Duplicate
                                </button>
                            </form>

                            {{-- Archive / Unarchive --}}
                            <form action="{{ route('admin.proposal-templates.archive', $template) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 neo-btn text-gray-600 font-bold text-[10px] uppercase" title="Archive / Toggle Status">
                                    {{ $template->status === 'archived' ? 'Activate' : 'Archive' }}
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form action="{{ route('admin.proposal-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this proposal template?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 neo-btn text-rose-600 hover:text-rose-800 font-bold text-[10px] uppercase" title="Delete Template">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-500 font-bold">
                        No proposal templates found. Click "Create New Template" to add one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($templates->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $templates->links() }}
    </div>
    @endif
</div>
@endsection
