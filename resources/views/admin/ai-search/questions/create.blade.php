@extends('admin.layouts.app')

@section('page-title', 'Add New Tourist Search Question')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-stone-900 font-display">Add Tourist Question</h1>
            <p class="text-xs text-stone-500">Create a question, link to tour package, and generate an AI answer with SEO metadata.</p>
        </div>
        <a href="{{ route('admin.ai-search.questions.index') }}" class="px-4 py-2 bg-stone-200 hover:bg-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all">
            ← Back to Questions
        </a>
    </div>

    <form method="POST" action="{{ route('admin.ai-search.questions.store') }}" id="question-form" class="space-y-6">
        @csrf

        {{-- Step 1: Question Core Info --}}
        <div class="neo-card p-6 space-y-4">
            <h3 class="font-bold text-base text-stone-900 border-b border-stone-200 pb-3">1. Question Details</h3>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Tourist Question *</label>
                <textarea name="question" id="question_input" rows="3" required class="w-full bg-white border border-stone-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="e.g. What is the best time of year to climb Mount Kilimanjaro via the Machame Route?"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Category *</label>
                    <select name="category_id" id="category_id_input" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Related Tour Package (Optional)</label>
                    <select name="tour_id" id="tour_id_input" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- No Specific Tour --</option>
                        @foreach($tours as $tour)
                            <option value="{{ $tour->id }}">{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Search Intent *</label>
                    <select name="search_intent" id="search_intent_input" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="informational">Informational (Seeking Info)</option>
                        <option value="commercial">Commercial (Comparing Operators)</option>
                        <option value="transactional">Transactional (Ready to Book)</option>
                        <option value="navigational">Navigational (Looking for Twina)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Target Country</label>
                    <select name="target_country" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="International">International (Global)</option>
                        <option value="USA">USA</option>
                        <option value="UK">UK</option>
                        <option value="Germany">Germany</option>
                        <option value="France">France</option>
                        <option value="Norway">Norway</option>
                        <option value="Australia">Australia</option>
                        <option value="Canada">Canada</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Target Website Location</label>
                    <select name="suggested_content_type" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="tour">Tour Details Page (FAQ)</option>
                        <option value="faq">Main Website FAQ Page</option>
                        <option value="blog">Blog Article</option>
                        <option value="destination">Destination Page</option>
                        <option value="company">Company / About Page</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- AI Generator Trigger Button --}}
        <div class="neo-card p-6 bg-gradient-to-r from-stone-900 to-stone-850 text-white flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-amber-400 text-sm flex items-center gap-2">
                    <span>✨ Twina AI Answer & SEO Generator</span>
                </h4>
                <p class="text-xs text-stone-300 mt-1">Click to automatically generate an expert luxury answer, SEO title, meta description, and keywords using AI.</p>
            </div>
            <button type="button" onclick="generateAiContent()" id="ai-gen-btn" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-6 py-3.5 rounded-2xl text-xs uppercase tracking-widest shadow-lg transition-all shrink-0 hover:scale-105 flex items-center gap-2">
                <span id="ai-btn-text">Generate AI Answer</span>
                <span id="ai-btn-spinner" class="hidden animate-spin">⏳</span>
            </button>
        </div>

        {{-- Step 2: Answer & SEO Output Fields --}}
        <div class="neo-card p-6 space-y-4">
            <h3 class="font-bold text-base text-stone-900 border-b border-stone-200 pb-3">2. Answer & SEO Metadata</h3>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Answer Title (Meta Title)</label>
                <input type="text" name="answer_title" id="answer_title" value="{{ old('answer_title') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none" placeholder="e.g. Best Time to Climb Kilimanjaro via Machame Route | Twina Safaris">
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Generated Answer Content *</label>
                <textarea name="answer_content" id="answer_content" rows="8" class="w-full bg-white border border-stone-300 rounded-xl p-4 text-sm focus:ring-2 focus:ring-amber-500 outline-none leading-relaxed" placeholder="Detailed answer generated by AI or written manually..."></textarea>
            </div>

            {{-- Preview Answer Button Below Editor --}}
            <div class="flex items-center justify-between p-3.5 bg-stone-100/90 border border-stone-200/80 rounded-2xl">
                <button type="button" onclick="openAnswerPreview()" class="bg-stone-900 hover:bg-stone-800 text-amber-400 font-black px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm hover:scale-105">
                    <span>👁️ Preview Answer (Public Website View)</span>
                </button>
                <span class="text-[11px] text-stone-500 font-medium italic">Preview mode does not publish or alter publication status.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2" class="w-full bg-white border border-stone-300 rounded-xl p-3.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none" placeholder="Short description for Google snippet (max 155 chars)..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Primary Keyword</label>
                    <input type="text" name="primary_keyword" id="primary_keyword" value="{{ old('primary_keyword') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none" placeholder="e.g. Machame Route best time">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Publication Status *</label>
                    <select name="status" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="draft">Draft (Saved for review)</option>
                        <option value="approved">Approved (Verified by Admin)</option>
                        <option value="published">Published (Live on Website)</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-8 py-4 rounded-2xl text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105">
                Save Search Question
            </button>
            <a href="{{ route('admin.ai-search.questions.index') }}" class="px-6 py-4 border border-stone-300 text-stone-600 rounded-2xl text-xs font-bold uppercase tracking-wider hover:bg-stone-200/60 transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

{{-- Answer Preview Modal --}}
<div id="answer-preview-modal" class="fixed inset-0 bg-stone-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl border border-stone-200/90 overflow-hidden relative flex flex-col max-h-[90vh]">
        {{-- Preview Header Bar --}}
        <div class="bg-stone-900 text-white p-5 px-6 flex items-center justify-between border-b border-stone-800 shrink-0">
            <div class="flex items-center gap-3">
                <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400 text-base">👁️</span>
                <div>
                    <h3 class="font-bold text-sm text-white">Public Website Answer Preview</h3>
                    <p class="text-[11px] text-stone-400">Live preview rendered with Twina Safaris frontend public styling</p>
                </div>
            </div>
            <button type="button" onclick="closeAnswerPreview()" class="w-8 h-8 rounded-full bg-stone-800 hover:bg-stone-700 text-stone-300 font-bold flex items-center justify-center transition-colors text-lg">
                &times;
            </button>
        </div>

        {{-- Preview Content Body --}}
        <div class="p-6 md:p-8 overflow-y-auto space-y-6 bg-[#faf9f6] flex-1">
            {{-- Google Search Result Snippet Card --}}
            <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs">
                <div class="text-[10px] uppercase font-black tracking-wider text-stone-400 mb-2">Google & AI Search Snippet Preview</div>
                <div class="text-xs text-emerald-700 font-mono mb-0.5 truncate" id="prev-url">https://twinasafaris.com › tour-faq</div>
                <div class="text-sky-800 font-bold text-base hover:underline cursor-pointer leading-tight mb-1" id="prev-meta-title">
                    Meta Title Preview
                </div>
                <div class="text-stone-600 text-xs leading-relaxed line-clamp-2" id="prev-meta-desc">
                    Meta Description Preview
                </div>
            </div>

            {{-- Public Website Frontend Rendering Card --}}
            <div class="bg-white border border-[#e8dfcf] rounded-3xl p-6 md:p-8 shadow-sm space-y-4">
                <div class="flex items-center gap-2 text-[#8A6D0B] text-[11px] font-black uppercase tracking-wider">
                    <span>✨ Verified Traveler Answer</span>
                    <span id="prev-kw-badge" class="bg-[#fbf7ee] px-2.5 py-0.5 rounded-full border border-[#f1e5cf] text-[#8A6D0B]">
                        Primary Keyword
                    </span>
                </div>

                <h2 class="font-display text-2xl md:text-3xl font-black text-stone-900 border-b-2 border-[#b8920d] pb-3 leading-tight" id="prev-question-title">
                    Question Title
                </h2>

                <div class="text-stone-700 text-sm md:text-base leading-relaxed space-y-3 font-medium max-w-none" id="prev-content-body">
                    Answer content preview
                </div>
            </div>
        </div>

        {{-- Preview Footer Action Buttons --}}
        <div class="p-5 px-6 bg-stone-100 border-t border-stone-200 flex items-center justify-between shrink-0">
            <button type="button" onclick="closeAnswerPreview()" class="px-5 py-2.5 border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                ✏️ Continue Editing
            </button>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeAnswerPreview()" class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                    ✕ Close Preview
                </button>
            </div>
        </div>
    </div>
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
            if (d.meta_title) document.getElementById('answer_title').value = d.meta_title;
            if (d.meta_description) document.getElementById('meta_description').value = d.meta_description;
            if (d.primary_keyword) document.getElementById('primary_keyword').value = d.primary_keyword;
            if (d.search_intent) document.getElementById('search_intent_input').value = d.search_intent;
        } else {
            alert('AI Generation failed. Please try writing manually.');
        }
    } catch (e) {
        console.error(e);
        alert('An error occurred during AI generation.');
    } finally {
        btnText.innerText = 'Generate AI Answer';
        spinner.classList.add('hidden');
        btn.disabled = false;
    }
}

function openAnswerPreview() {
    const question = document.getElementById('question_input').value.trim() || 'Tourist Question';
    const answerContent = document.getElementById('answer_content').value.trim() || 'No answer content entered yet.';
    const metaTitle = document.getElementById('answer_title').value.trim() || question;
    const metaDesc = document.getElementById('meta_description').value.trim() || 'Experience an unforgettable safari journey with Twina Safaris Tanzania.';
    const primaryKw = document.getElementById('primary_keyword').value.trim() || 'Tanzania Safari';

    document.getElementById('prev-question-title').innerText = question;
    document.getElementById('prev-meta-title').innerText = metaTitle;
    document.getElementById('prev-meta-desc').innerText = metaDesc;
    document.getElementById('prev-kw-badge').innerText = primaryKw;

    const formattedContent = answerContent
        .split('\n\n')
        .map(p => `<p class="mb-3">${p.replace(/\n/g, '<br>')}</p>`)
        .join('');

    document.getElementById('prev-content-body').innerHTML = formattedContent;

    const modal = document.getElementById('answer-preview-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeAnswerPreview() {
    const modal = document.getElementById('answer-preview-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}
</script>
@endsection
