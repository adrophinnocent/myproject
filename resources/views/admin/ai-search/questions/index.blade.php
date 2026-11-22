@extends('admin.layouts.app')

@section('page-title', 'Tourist Search Questions Management')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-stone-900 font-display">Tourist Search Questions</h1>
            <p class="text-xs text-stone-500">Manage tourist search queries, AI generated answers, keywords, and publication status.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ai-search.dashboard') }}" class="px-4 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all">
                ← Dashboard
            </a>
            <a href="{{ route('admin.ai-search.questions.create') }}" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                + Add Question
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="neo-card p-4">
        <form method="GET" action="{{ route('admin.ai-search.questions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions or keywords..." class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
            </div>

            <div>
                <select name="category_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <div>
                <select name="search_intent" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                    <option value="">All Search Intent</option>
                    <option value="informational" {{ request('search_intent') === 'informational' ? 'selected' : '' }}>Informational</option>
                    <option value="commercial" {{ request('search_intent') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="transactional" {{ request('search_intent') === 'transactional' ? 'selected' : '' }}>Transactional</option>
                    <option value="navigational" {{ request('search_intent') === 'navigational' ? 'selected' : '' }}>Navigational</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold py-2 rounded-xl text-xs transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'status', 'search_intent']))
                    <a href="{{ route('admin.ai-search.questions.index') }}" class="p-2 bg-stone-200 hover:bg-stone-300 rounded-xl text-stone-700 text-xs font-bold" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Questions Table --}}
    <div class="neo-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-stone-100 text-stone-700 uppercase font-black tracking-wider text-[10px] border-b border-stone-200">
                        <th class="p-4">Tourist Question</th>
                        <th class="p-4">Category & Tour</th>
                        <th class="p-4">Primary Keyword</th>
                        <th class="p-4">Search Intent</th>
                        <th class="p-4">AI Answer</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($questions as $q)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-4">
                                <a href="{{ route('admin.ai-search.questions.edit', $q) }}" class="font-bold text-stone-900 hover:text-amber-600 block leading-snug">
                                    {{ $q->question }}
                                </a>
                                @if($q->target_country)
                                    <span class="text-[10px] font-semibold text-stone-400">Target: {{ $q->target_country }}</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <span class="font-bold text-stone-800 block">{{ $q->category->name ?? 'General' }}</span>
                                @if($q->tour)
                                    <span class="text-[10px] text-amber-600 font-semibold block truncate max-w-xs">📍 {{ $q->tour->title }}</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-1 bg-stone-100 text-stone-800 font-medium rounded-md text-[11px] inline-block border border-stone-200">
                                    🔑 {{ $q->primary_keyword ?: 'N/A' }}
                                </span>
                            </td>

                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase
                                    {{ $q->search_intent === 'transactional' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $q->search_intent === 'commercial' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $q->search_intent === 'informational' ? 'bg-sky-100 text-sky-800' : '' }}
                                    {{ $q->search_intent === 'navigational' ? 'bg-amber-100 text-amber-800' : '' }}">
                                    {{ $q->search_intent }}
                                </span>
                            </td>

                            <td class="p-4">
                                @if($q->answer && !empty($q->answer->content))
                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                        <span>✓ Generated</span>
                                        @if($q->answer->is_approved)
                                            <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded uppercase">Approved</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-amber-600 font-semibold italic">Pending AI</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $q->status === 'published' ? 'bg-emerald-500 text-white' : '' }}
                                    {{ $q->status === 'approved' ? 'bg-sky-500 text-white' : '' }}
                                    {{ $q->status === 'draft' ? 'bg-stone-200 text-stone-700' : '' }}
                                    {{ $q->status === 'archived' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ $q->status }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.ai-search.questions.edit', $q) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 font-bold rounded-lg text-xs transition-all">
                                        Edit / AI
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai-search.questions.destroy', $q) }}" onsubmit="return confirm('Delete this search question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-600 transition-colors">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-stone-400 italic">No search questions found matching your filter criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-stone-200">
            {{ $questions->links() }}
        </div>
    </div>
</div>
@endsection
