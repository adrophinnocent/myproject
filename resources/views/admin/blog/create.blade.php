@extends('admin.layouts.app')

@section('title', 'Create Blog Post')
@section('page-title', 'Create New Story')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'main' }">
    {{-- Tab Navigation --}}
    <div class="flex gap-4 mb-6">
        <button @click="tab = 'main'" :class="tab === 'main' ? 'bg-safari-dark text-white' : 'bg-white text-gray-500 border border-gray-200'" class="px-6 py-2 rounded-xl font-bold text-sm transition-all shadow-sm">
            Main Content
        </button>
        <button @click="tab = 'translate'" :class="tab === 'translate' ? 'bg-gold-500 text-safari-dark' : 'bg-white text-gray-500 border border-gray-200'" class="px-6 py-2 rounded-xl font-bold text-sm transition-all shadow-sm">
            🌍 Translations
        </button>
    </div>

    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Main Content Tab --}}
        <div x-show="tab === 'main'" class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Story Title</label>
                    <input type="text" id="blog-title" name="title" value="{{ old('title') }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold text-gray-900"
                           placeholder="Enter a compelling title">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">URL Slug</label>
                    <input type="text" id="blog-slug" name="slug" value="{{ old('slug') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-mono text-sm"
                           placeholder="auto-generated-from-title">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Category</label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Choose a category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Cover Image</label>
                    <div class="relative">
                        <input type="file" name="featured_image" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3.5 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-gold-500 file:text-safari-dark hover:file:bg-gold-600 transition-all">
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Recommended size: 1200x800px</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Short Summary (Excerpt)</label>
                <textarea name="excerpt" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all leading-relaxed" placeholder="A brief hook for the story cards...">{{ old('excerpt') }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Full Story Content</label>
                <textarea name="content" id="editor-main" rows="15" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all leading-relaxed" placeholder="Write your masterpiece here...">{{ old('content') }}</textarea>
            </div>

            <div class="flex items-center gap-4 p-6 bg-gold-50/50 rounded-2xl border border-gold-100">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gold-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
                    <span class="ms-3 text-sm font-black text-safari-dark uppercase tracking-widest">Publish Immediately</span>
                </label>
            </div>
        </div>

        {{-- Translation Tab --}}
        <div x-show="tab === 'translate'" class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-10" x-data="{ lang: 'de' }">
            <div class="flex gap-2 mb-10 border-b border-gray-100 pb-6 overflow-x-auto scrollbar-hide">
                @foreach(['de' => '🇩🇪 German', 'fr' => '🇫🇷 French', 'es' => '🇪🇸 Spanish', 'it' => '🇮🇹 Italian', 'zh' => '🇨🇳 Chinese', 'nl' => '🇳🇱 Dutch'] as $code => $label)
                    <button type="button" @click="lang = '{{ $code }}'"
                            :class="lang === '{{ $code }}' ? 'bg-gold-500 text-safari-dark border-gold-500' : 'bg-white text-gray-400 border-gray-100 hover:border-gold-200'"
                            class="px-5 py-3 rounded-xl border font-black text-[10px] uppercase tracking-widest transition-all shadow-sm">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @foreach(['de', 'fr', 'es', 'it', 'zh', 'nl'] as $locale)
            <div x-show="lang === '{{ $locale }}'" class="space-y-8 animate-fadeIn">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="translations[{{ $locale }}][title]" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold text-gray-900">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Excerpt ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][excerpt]" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all leading-relaxed"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Story Content ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][content]" id="editor-{{ $locale }}" rows="12" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all leading-relaxed"></textarea>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 flex gap-6">
            <button type="submit" class="flex-1 bg-safari-dark hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-2xl shadow-black/20 uppercase tracking-[0.2em] text-sm">
                Launch This Story
            </button>
            <a href="{{ route('admin.blog.index') }}" class="px-10 py-5 bg-white border border-gray-200 text-gray-400 hover:text-gray-600 rounded-2xl font-black uppercase tracking-widest text-sm transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
function initEditor(selector) {
    ClassicEditor
        .create(document.querySelector(selector), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
        })
        .catch(error => {
            console.error(error);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    initEditor('#editor-main');
    @foreach(['de', 'fr', 'es', 'it', 'zh', 'nl'] as $locale)
    initEditor('#editor-{{ $locale }}');
    @endforeach
});

function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

const titleInput = document.getElementById('blog-title');
const slugInput = document.getElementById('blog-slug');

if (titleInput && slugInput) {
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.auto === 'true') {
            slugInput.value = slugify(this.value);
            slugInput.dataset.auto = 'true';
        }
    });
    slugInput.addEventListener('input', () => { slugInput.dataset.auto = 'false'; });
}
</script>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
.ck-editor__editable {
    min-height: 400px;
    background-color: #f9fafb !important;
    border-radius: 0 0 0.75rem 0.75rem !important;
    border: 1px solid #e5e7eb !important;
}
.ck-toolbar {
    background-color: #ffffff !important;
    border-radius: 0.75rem 0.75rem 0 0 !important;
    border: 1px solid #e5e7eb !important;
}
</style>
@endsection
