@extends('admin.layouts.app')

@section('page-title', 'AI Platform Visibility Tracker')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-stone-900 font-display">AI Visibility Tracker</h1>
            <p class="text-xs text-stone-500">Record manual & automated tests on ChatGPT, Gemini, and Perplexity to track if Twina Safaris is recommended.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ai-search.dashboard') }}" class="px-4 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all">
                ← Dashboard
            </a>
            <button onclick="document.getElementById('new-check-modal').classList.remove('hidden')" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                + Record AI Test
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="neo-card p-4">
            <span class="text-[10px] font-black uppercase text-stone-400">Total AI Tests</span>
            <div class="text-2xl font-black text-stone-900 mt-1">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="neo-card p-4">
            <span class="text-[10px] font-black uppercase text-emerald-600">Twina Mentioned</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['mentioned_count']) }}</div>
        </div>
        <div class="neo-card p-4">
            <span class="text-[10px] font-black uppercase text-sky-600">Link Cited</span>
            <div class="text-2xl font-black text-sky-600 mt-1">{{ number_format($stats['cited_count']) }}</div>
        </div>
        <div class="neo-card p-4">
            <span class="text-[10px] font-black uppercase text-amber-600">ChatGPT Tests</span>
            <div class="text-2xl font-black text-stone-800 mt-1">{{ number_format($stats['chatgpt']) }}</div>
        </div>
        <div class="neo-card p-4">
            <span class="text-[10px] font-black uppercase text-purple-600">Perplexity / Gemini</span>
            <div class="text-2xl font-black text-stone-800 mt-1">{{ number_format($stats['perplexity'] + $stats['gemini']) }}</div>
        </div>
    </div>

    {{-- Visibility Checks Table --}}
    <div class="neo-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-stone-100 text-stone-700 uppercase font-black tracking-wider text-[10px] border-b border-stone-200">
                        <th class="p-4">Test Query</th>
                        <th class="p-4">AI Platform</th>
                        <th class="p-4">Brand Mentioned?</th>
                        <th class="p-4">Link Cited?</th>
                        <th class="p-4">Test Date</th>
                        <th class="p-4">Notes & Source Link</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($checks as $check)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-4 font-bold text-stone-900 max-w-sm">
                                "{{ $check->query_text }}"
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-stone-200 text-stone-900 font-bold rounded-lg text-[10px]">
                                    {{ $check->ai_platform }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($check->is_brand_mentioned)
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px]">
                                        ✓ Yes (Mentioned)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-700 font-bold rounded-full text-[10px]">
                                        × Not Mentioned
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($check->is_source_cited)
                                    <span class="px-2.5 py-1 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px]">
                                        🔗 Link Cited
                                    </span>
                                @else
                                    <span class="text-stone-400 text-[10px]">No link</span>
                                @endif
                            </td>
                            <td class="p-4 text-stone-600 font-medium">
                                {{ $check->check_date ? $check->check_date->format('M d, Y') : '' }}
                            </td>
                            <td class="p-4 max-w-xs truncate text-stone-600">
                                {{ $check->notes ?: 'N/A' }}
                                @if($check->source_link)
                                    <a href="{{ $check->source_link }}" target="_blank" class="text-amber-600 hover:underline block text-[10px]">View Source ↗</a>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <form method="POST" action="{{ route('admin.ai-search.visibility.destroy', $check) }}" onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-600">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-stone-400 italic">No AI platform visibility checks recorded yet. Click "+ Record AI Test" to add one!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-stone-200">
            {{ $checks->links() }}
        </div>
    </div>
</div>

{{-- Modal for Recording New Test --}}
<div id="new-check-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h3 class="font-black text-stone-900 text-lg">Record AI Platform Test Result</h3>
            <button onclick="document.getElementById('new-check-modal').classList.add('hidden')" class="text-stone-400 hover:text-stone-700 text-xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.ai-search.visibility.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Query / Question Tested *</label>
                <input type="text" name="query_text" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none" placeholder="e.g. Best local tour operator for Serengeti private safari">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">AI Platform *</label>
                    <select name="ai_platform" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="ChatGPT">ChatGPT (OpenAI)</option>
                        <option value="Gemini">Gemini (Google)</option>
                        <option value="Perplexity">Perplexity AI</option>
                        <option value="Copilot">Microsoft Copilot</option>
                        <option value="Claude">Claude (Anthropic)</option>
                        <option value="Other">Other AI Engine</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Test Date *</label>
                    <input type="date" name="check_date" value="{{ date('Y-m-d') }}" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
            </div>

            <div class="space-y-2 pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-stone-800">
                    <input type="checkbox" name="is_brand_mentioned" value="1" class="rounded text-amber-500 focus:ring-amber-500 w-4 h-4">
                    <span>Twina Safaris was mentioned in the response</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-stone-800">
                    <input type="checkbox" name="is_source_cited" value="1" class="rounded text-amber-500 focus:ring-amber-500 w-4 h-4">
                    <span>Website URL (twinasafaris.com) was cited as a source</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Source Link (Optional)</label>
                <input type="url" name="source_link" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-amber-500 outline-none" placeholder="https://chatgpt.com/share/...">
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Notes / Competitors Mentioned</label>
                <textarea name="notes" rows="2" class="w-full bg-stone-50 border border-stone-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500 outline-none" placeholder="Notes on competitors mentioned or AI answer summary..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-stone-950 font-black py-3 rounded-xl text-xs uppercase tracking-wider shadow-md">
                    Save Test Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
