@extends('admin.layouts.app')

@section('page-title', 'AI Search Intelligence System')

@section('content')
<div class="space-y-8">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-stone-900 via-stone-850 to-stone-900 rounded-3xl p-6 md:p-8 text-white shadow-xl border border-stone-800 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3">
                    ✨ Generative Engine Optimization (GEO) & Search Intelligence
                </div>
                <h1 class="text-2xl md:text-4xl font-black font-display tracking-tight text-white leading-tight">
                    Twina AI Search Intelligence
                </h1>
                <p class="text-stone-300 text-xs md:text-sm max-w-2xl mt-2 leading-relaxed">
                    Track, curate, and optimize tourist questions asked on Google, ChatGPT, Gemini, and Perplexity to boost Twina Safaris visibility and drive high-intent safari bookings.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('admin.ai-search.questions.create') }}" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-6 py-3.5 rounded-2xl text-xs uppercase tracking-widest shadow-lg transition-all hover:scale-105 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add Question</span>
                </a>
                <a href="{{ route('admin.ai-search.visibility.index') }}" class="bg-stone-800 hover:bg-stone-750 text-stone-200 border border-stone-700 font-bold px-5 py-3.5 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>AI Visibility Tracker</span>
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total Questions --}}
        <div class="neo-card p-5 flex flex-col justify-between">
            <span class="text-[10px] font-black uppercase tracking-wider text-stone-400">Total Questions</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-stone-900">{{ number_format($totalQuestions) }}</span>
                <span class="p-2 rounded-xl bg-stone-100 text-stone-700 text-sm">❓</span>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-2">Across 15 categories</p>
        </div>

        {{-- Drafts --}}
        <div class="neo-card p-5 flex flex-col justify-between">
            <span class="text-[10px] font-black uppercase tracking-wider text-amber-600">Draft Questions</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-amber-600">{{ number_format($draftCount) }}</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600 text-sm">📝</span>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-2">Awaiting AI generation</p>
        </div>

        {{-- Approved --}}
        <div class="neo-card p-5 flex flex-col justify-between">
            <span class="text-[10px] font-black uppercase tracking-wider text-sky-600">Approved Answers</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-sky-600">{{ number_format($approvedCount) }}</span>
                <span class="p-2 rounded-xl bg-sky-50 text-sky-600 text-sm">✓</span>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-2">Ready for publication</p>
        </div>

        {{-- Published --}}
        <div class="neo-card p-5 flex flex-col justify-between">
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Published Live</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-emerald-600">{{ number_format($publishedCount) }}</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-sm">🌐</span>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-2">Indexed on site & schema</p>
        </div>

        {{-- AI Visibility Rate --}}
        <div class="neo-card p-5 flex flex-col justify-between border-l-4 border-amber-500">
            <span class="text-[10px] font-black uppercase tracking-wider text-stone-700">AI Mention Rate</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-amber-600">{{ $mentionRate }}%</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-700 text-sm">🎯</span>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-2">{{ $visibilityStats->brand_mentions ?? 0 }} / {{ $visibilityStats->total_checks ?? 0 }} tests mentioned Twina</p>
        </div>
    </div>

    {{-- Category Breakdown Grid --}}
    <div class="neo-card p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 border-b border-stone-200 pb-4">
            <div>
                <h3 class="font-bold text-lg text-stone-900">Questions by Safari Category</h3>
                <p class="text-xs text-stone-500">Categorized tourist search queries & intent targets</p>
            </div>
            <a href="{{ route('admin.ai-search.questions.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                <span>View All Questions</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            @foreach($categories as $cat)
                <a href="{{ route('admin.ai-search.questions.index', ['category_id' => $cat->id]) }}" class="p-4 rounded-2xl bg-stone-50 border border-stone-200/80 hover:border-amber-500/60 transition-all hover:shadow-sm flex flex-col justify-between group">
                    <div>
                        <h4 class="font-bold text-stone-900 text-xs group-hover:text-amber-600 transition-colors leading-snug">
                            {{ $cat->name }}
                        </h4>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-[11px] font-black text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/60">
                            {{ $cat->questions_count }} {{ Str::plural('Qn', $cat->questions_count) }}
                        </span>
                        <span class="text-stone-300 group-hover:text-amber-500 transition-colors text-xs">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent Activity Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Questions (2 cols) --}}
        <div class="lg:col-span-2 neo-card p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
                <h3 class="font-bold text-stone-900 text-base">Recent Tourist Questions</h3>
                <a href="{{ route('admin.ai-search.questions.index') }}" class="text-xs font-bold text-amber-600">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-stone-400 uppercase font-black tracking-wider text-[10px] border-b border-stone-200">
                            <th class="pb-3">Question</th>
                            <th class="pb-3">Category</th>
                            <th class="pb-3">Intent</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($recentQuestions as $rq)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 font-bold text-stone-900 max-w-xs truncate">
                                    {{ $rq->question }}
                                </td>
                                <td class="py-3 text-stone-600 font-medium">
                                    {{ $rq->category->name ?? 'General' }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                                        {{ $rq->search_intent === 'transactional' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $rq->search_intent === 'commercial' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $rq->search_intent === 'informational' ? 'bg-sky-100 text-sky-800' : '' }}
                                        {{ $rq->search_intent === 'navigational' ? 'bg-amber-100 text-amber-800' : '' }}">
                                        {{ $rq->search_intent }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                                        {{ $rq->status === 'published' ? 'bg-emerald-500 text-white' : '' }}
                                        {{ $rq->status === 'approved' ? 'bg-sky-500 text-white' : '' }}
                                        {{ $rq->status === 'draft' ? 'bg-stone-200 text-stone-700' : '' }}
                                        {{ $rq->status === 'archived' ? 'bg-rose-100 text-rose-700' : '' }}">
                                        {{ $rq->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.ai-search.questions.edit', $rq) }}" class="text-amber-600 hover:text-amber-700 font-bold">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-stone-400 italic">No questions added yet. Click "+ Add Question" to start!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AI Visibility Tests --}}
        <div class="neo-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
                    <h3 class="font-bold text-stone-900 text-base">AI Platform Tracker</h3>
                    <a href="{{ route('admin.ai-search.visibility.index') }}" class="text-xs font-bold text-amber-600">+ Test</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentVisibilityChecks as $vc)
                        <div class="p-3.5 rounded-2xl bg-stone-50 border border-stone-200/80 text-xs">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-black text-stone-900 px-2 py-0.5 rounded-md bg-stone-200 text-[10px]">
                                    {{ $vc->ai_platform }}
                                </span>
                                <span class="text-[10px] font-bold text-stone-400">
                                    {{ $vc->check_date ? $vc->check_date->format('M d, Y') : '' }}
                                </span>
                            </div>
                            <p class="font-semibold text-stone-800 line-clamp-2 mb-2">"{{ $vc->query_text }}"</p>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $vc->is_brand_mentioned ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $vc->is_brand_mentioned ? '✓ Twina Mentioned' : '× Not Mentioned' }}
                                </span>
                                @if($vc->is_source_cited)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800">
                                        🔗 Link Cited
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400 italic py-6 text-center">No AI visibility checks recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-stone-200 mt-6">
                <a href="{{ route('admin.ai-search.visibility.index') }}" class="w-full block text-center py-2.5 rounded-xl bg-stone-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-stone-800 transition-all">
                    Record New AI Test
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
