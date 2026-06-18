@extends('admin.layouts.app')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Post: ' . $blog->title)

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'main' }">
    <div class="flex gap-4 mb-6">
        <button @click="tab = 'main'" :class="tab === 'main' ? 'bg-safari-dark text-white' : 'bg-white text-gray-500 border border-gray-200'" class="px-6 py-2 rounded-xl font-bold text-sm transition-all shadow-sm">
            Main Content
        </button>
        <button @click="tab = 'translate'" :class="tab === 'translate' ? 'bg-gold-500 text-safari-dark' : 'bg-white text-gray-500 border border-gray-200'" class="px-6 py-2 rounded-xl font-bold text-sm transition-all shadow-sm">
            🌍 Translations
        </button>
    </div>

    <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Main Tab --}}
        <div x-show="tab === 'main'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Post Title</label>
                <input type="text" id="blog-title" name="title" value="{{ old('title', $blog->title) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                <input type="text" id="blog-slug" name="slug" value="{{ old('slug', $blog->slug) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Featured Image</label>
                    <div class="flex items-center gap-4">
                        @if($blog->featured_image)
                        <img src="{{ $blog->featured_image_url }}" class="w-16 h-12 object-cover rounded border border-gray-200">
                        @endif
                        <input type="file" name="featured_image" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Excerpt (Short Summary)</label>
                <textarea name="excerpt" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                <textarea name="content" id="editor-main" rows="12" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('content', $blog->content) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }} class="w-5 h-5 text-[#D4AF37] rounded focus:ring-[#D4AF37]">
                    <span class="text-sm font-semibold text-gray-700">Published</span>
                </label>
            </div>
        </div>

        {{-- Translation Tab --}}
        <div x-show="tab === 'translate'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8" x-data="{ lang: 'de' }">
            <div class="flex gap-2 mb-8 border-b border-gray-100 pb-4 overflow-x-auto">
                @foreach(['de' => '🇩🇪 German', 'fr' => '🇫🇷 French', 'es' => '🇪🇸 Spanish', 'it' => '🇮🇹 Italian', 'zh' => '🇨🇳 Chinese', 'nl' => '🇳🇱 Dutch'] as $code => $label)
                    <button type="button" @click="lang = '{{ $code }}'" :class="lang === '{{ $code }}' ? 'bg-gold-50 border-gold-500 text-gold-700' : 'bg-white text-gray-400 border-gray-100'" class="px-4 py-2 rounded-lg border font-bold text-xs transition-all">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @foreach(['de', 'fr', 'es', 'it', 'zh', 'nl'] as $locale)
            <div x-show="lang === '{{ $locale }}'" class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="translations[{{ $locale }}][title]" value="{{ $blog->translate('title', $locale) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Excerpt ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][excerpt]" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">{{ $blog->translate('excerpt', $locale) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Content ({{ strtoupper($locale) }})</label>
                    <textarea name="translations[{{ $locale }}][content]" id="editor-{{ $locale }}" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-gold-500/20 outline-none">{{ $blog->translate('content', $locale) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-safari-dark hover:bg-black text-white font-bold px-12 py-4 rounded-xl transition-all shadow-xl">
                Save Changes
            </button>
            <a href="{{ route('admin.blog.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-8 py-4 rounded-xl transition-colors">
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
const originalSlug = '{{ $blog->slug }}';

if (titleInput && slugInput) {
    titleInput.addEventListener('input', function() {
        const currentSlug = slugInput.value;
        if (!currentSlug || currentSlug === originalSlug) {
            slugInput.value = slugify(this.value);
        }
    });
}
</script>

<style>
.ck-editor__editable {
    min-height: 400px;
    background-color: #f9fafb !important;
}
</style>
@endsection
