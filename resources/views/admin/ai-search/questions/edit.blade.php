@extends('admin.layouts.app')

@section('page-title', 'Edit Tourist Search Question')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-stone-900 font-display">Edit Tourist Search Question</h1>
            <p class="text-xs text-stone-500">Update question details, AI answer, keywords, and publication target.</p>
        </div>
        <a href="{{ route('admin.ai-search.questions.index') }}" class="px-4 py-2 bg-stone-200 hover:bg-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all">
            ← Back to Questions
        </a>
    </div>

    <form method="POST" action="{{ route('admin.ai-search.questions.update', $question) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Question Core Info --}}
        <div class="neo-card p-6 space-y-4">
            <h3 class="font-bold text-base text-stone-900 border-b border-stone-200 pb-3">1. Question Configuration</h3>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Tourist Question *</label>
                <textarea name="question" id="question_input" rows="3" required class="w-full bg-white border border-stone-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">{{ old('question', $question->question) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Category *</label>
                    <select name="category_id" id="category_id_input" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $question->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Linked Tour Package</label>
                    <select name="tour_id" id="tour_id_input" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- No Specific Tour --</option>
                        @foreach($tours as $tour)
                            <option value="{{ $tour->id }}" {{ $question->tour_id == $tour->id ? 'selected' : '' }}>{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Search Intent *</label>
                    <select name="search_intent" id="search_intent_input" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="informational" {{ $question->search_intent === 'informational' ? 'selected' : '' }}>Informational</option>
                        <option value="commercial" {{ $question->search_intent === 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="transactional" {{ $question->search_intent === 'transactional' ? 'selected' : '' }}>Transactional</option>
                        <option value="navigational" {{ $question->search_intent === 'navigational' ? 'selected' : '' }}>Navigational</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Target Country</label>
                    <input type="text" name="target_country" value="{{ old('target_country', $question->target_country ?: 'International') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Target Website Location</label>
                    <select name="suggested_content_type" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="tour" {{ $question->suggested_content_type === 'tour' ? 'selected' : '' }}>Tour Details Page (FAQ)</option>
                        <option value="faq" {{ $question->suggested_content_type === 'faq' ? 'selected' : '' }}>Main Website FAQ Page</option>
                        <option value="blog" {{ $question->suggested_content_type === 'blog' ? 'selected' : '' }}>Blog Article</option>
                        <option value="destination" {{ $question->suggested_content_type === 'destination' ? 'selected' : '' }}>Destination Page</option>
                        <option value="company" {{ $question->suggested_content_type === 'company' ? 'selected' : '' }}>Company / About Page</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- AI Generator Banner --}}
        <div class="neo-card p-6 bg-gradient-to-r from-stone-900 to-stone-850 text-white flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-amber-400 text-sm flex items-center gap-2">
                    <span>✨ Regenerate Answer with AI</span>
                </h4>
                <p class="text-xs text-stone-300 mt-1">Re-run OpenAI optimization to update answer text and SEO keywords.</p>
            </div>
            <button type="button" onclick="generateAiContent()" id="ai-gen-btn" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-6 py-3.5 rounded-2xl text-xs uppercase tracking-widest shadow-lg transition-all shrink-0 hover:scale-105 flex items-center gap-2">
                <span id="ai-btn-text">Regenerate AI Answer</span>
                <span id="ai-btn-spinner" class="hidden animate-spin">⏳</span>
            </button>
        </div>

        {{-- Answer Details --}}
        <div class="neo-card p-6 space-y-4">
            <h3 class="font-bold text-base text-stone-900 border-b border-stone-200 pb-3">2. Approved Answer & SEO Snippet</h3>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Answer Title (Meta Title)</label>
                <input type="text" name="answer_title" id="answer_title" value="{{ old('answer_title', $question->answer->title ?? '') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Answer Content *</label>
                <textarea name="answer_content" id="answer_content" rows="8" required class="w-full bg-white border border-stone-300 rounded-xl p-4 text-sm focus:ring-2 focus:ring-amber-500 outline-none leading-relaxed">{{ old('answer_content', $question->answer->content ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2" class="w-full bg-white border border-stone-300 rounded-xl p-3.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">{{ old('meta_description', $question->answer->meta_description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Primary Keyword</label>
                    <input type="text" name="primary_keyword" id="primary_keyword" value="{{ old('primary_keyword', $question->primary_keyword) }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Status *</label>
                    <select name="status" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="draft" {{ $question->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="approved" {{ $question->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="published" {{ $question->status === 'published' ? 'selected' : '' }}>Published (Live)</option>
                        <option value="archived" {{ $question->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Google Search Snippet Preview --}}
        <div class="neo-card p-6 bg-stone-50 border border-stone-200">
            <h4 class="text-xs font-black text-stone-400 uppercase tracking-wider mb-3">Google & AI Search Snippet Preview</h4>
            <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-xs max-w-2xl">
                <div class="text-xs text-stone-500 font-mono mb-1">https://twinasafaris.com › {{ $question->slug }}</div>
                <div id="pv-title" class="text-sky-800 hover:underline font-bold text-base cursor-pointer">
                    {{ $question->answer->title ?? $question->question }}
                </div>
                <div id="pv-desc" class="text-stone-600 text-xs mt-1 leading-relaxed line-clamp-2">
                    {{ $question->answer->meta_description ?? 'Learn expert tips and recommendations for Tanzania safaris with Twina Safaris.' }}
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-8 py-4 rounded-2xl text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105">
                Update & Save Question
            </button>
            <a href="{{ route('admin.ai-search.questions.index') }}" class="px-6 py-4 border border-stone-300 text-stone-600 rounded-2xl text-xs font-bold uppercase tracking-wider hover:bg-stone-200/60 transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
async function generateAiContent() {
    const question = document.getElementById('question_input').value.trim();
    if (!question) {
        alert('Please enter a tourist question first!');
        return;
    }

    const categoryId = document.getElementById('category_id_input').value;
    const tourId = document.getElementById('tour_id_input').value;

    const btnText = document.getElementById('ai-btn-text');
    const spinner = document.getElementById('ai-btn-spinner');
    const btn = document.getElementById('ai-gen-btn');

    btnText.innerText = 'Generating with AI...';
    spinner.classList.remove('hidden');
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('admin.ai-search.generate-answer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                question: question,
                category_id: categoryId,
                tour_id: tourId
            })
        });

        const res = await response.json();

        if (res.success && res.data) {
            const d = res.data;
            if (d.content) document.getElementById('answer_content').value = d.content;
            if (d.meta_title) {
                document.getElementById('answer_title').value = d.meta_title;
                document.getElementById('pv-title').innerText = d.meta_title;
            }
            if (d.meta_description) {
                document.getElementById('meta_description').value = d.meta_description;
                document.getElementById('pv-desc').innerText = d.meta_description;
            }
            if (d.primary_keyword) document.getElementById('primary_keyword').value = d.primary_keyword;
            if (d.search_intent) document.getElementById('search_intent_input').value = d.search_intent;
        } else {
            alert('AI Generation failed. Please try writing manually.');
        }
    } catch (e) {
        console.error(e);
        alert('An error occurred during AI generation.');
    } finally {
        btnText.innerText = 'Regenerate AI Answer';
        spinner.classList.add('hidden');
        btn.disabled = false;
    }
}
</script>
@endsection
