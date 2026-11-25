@php
    $editing = ! empty($blog);
    $editorConfig = [
        'title' => old('title', $blog->title ?? ''),
        'slug' => old('slug', $blog->slug ?? ''),
        'excerpt' => old('excerpt', $blog->excerpt ?? ''),
        'metaTitle' => old('meta_title', $blog->meta_title ?? ''),
        'metaDescription' => old('meta_description', $blog->meta_description ?? ''),
        'tags' => old('tags', $editing ? implode(', ', (array) ($blog->tags ?? [])) : ''),
        'cover' => $editing ? ($blog->featured_image_url ?? '') : '',
        'content' => old('content', $blog->content ?? ''),
        'uploadUrl' => route('admin.blog.upload-image'),
        'csrf' => csrf_token(),
    ];
@endphp

{{-- Config is emitted in a script tag so JSON quotes cannot break the HTML attribute. --}}
<script>
    window.blogStoryEditorConfig = @json($editorConfig);
</script>

<div class="max-w-5xl mx-auto pb-16" x-data="blogStoryEditor(window.blogStoryEditorConfig)">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-serif-title font-bold text-stone-900">{{ $editorTitle }}</h1>
            <p class="text-xs text-stone-500 font-medium">{{ $editorSubtitle }}</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="px-4 py-2 bg-white border border-stone-200 rounded-xl text-xs font-bold text-stone-600 uppercase hover:border-[#d4af37] transition-all">Back to Stories</a>
    </div>

    <form method="POST" action="{{ $editorAction }}" enctype="multipart/form-data">
        @csrf
        @if($editing) @method('PUT') @endif

        {{-- Generated HTML is submitted as one field; blocks above are the source of truth. --}}
        <input type="hidden" name="content" :value="generateHtmlContent()">

        {{-- CARD 1: STORY META --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-6">
            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">STORY TITLE *</label>
                <input type="text" name="title" x-model="title" @input="updateSlug()" required
                       placeholder="e.g. Best Time to Go on Safari in Tanzania"
                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-sm font-bold text-stone-900 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">URL SLUG</label>
                <div class="flex items-center bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-[#d4af37]/30 focus-within:border-[#d4af37]">
                    <span class="px-3.5 py-3 text-xs font-bold text-stone-500 bg-[#f4ebd9] border-r border-[#e2d5bd] shrink-0">twinasafaris.com/blog/</span>
                    <input type="text" name="slug" x-model="slug" placeholder="auto-from-title"
                           class="w-full bg-transparent px-3 py-3 text-xs font-mono font-medium text-stone-800 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">CATEGORY *</label>
                    <select name="category_id" required class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-xs font-bold text-stone-900 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none cursor-pointer">
                        <option value="">Choose a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $editing ? $blog->category_id : null) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">COVER IMAGE</label>
                        <span class="text-[10px] font-bold text-stone-400">1200x800px · hero banner</span>
                    </div>
                    <input type="file" name="featured_image" id="cover_image_input" accept="image/*" class="hidden" @change="previewCover($event)" @if(!$editing) required @endif>
                    <div @click="document.getElementById('cover_image_input').click()"
                         class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-2xl p-5 text-center cursor-pointer transition-all flex flex-col items-center justify-center min-h-[110px] relative overflow-hidden">
                        <template x-if="coverPreview">
                            <div class="w-full h-28 relative">
                                <img :src="coverPreview" class="w-full h-full object-cover rounded-xl shadow-xs" alt="Cover preview">
                                <span class="absolute bottom-1 right-1 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-0.5 rounded">Change cover</span>
                            </div>
                        </template>
                        <template x-if="!coverPreview">
                            <div>
                                <svg class="w-7 h-7 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-black text-[#a88210] uppercase tracking-wider">CLICK TO UPLOAD COVER</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">TAGS <span class="normal-case font-normal text-stone-400">comma separated · helps readers find related stories</span></label>
                <input type="text" name="tags" value="{{ old('tags', $editing ? implode(', ', (array) ($blog->tags ?? [])) : '') }}"
                       placeholder="Serengeti, Big Five, Safari Tips"
                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none">
            </div>
        </div>

        {{-- CARD 2: EXCERPT --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">SHORT SUMMARY (EXCERPT)</label>
                <span class="text-[10px] font-mono font-bold text-stone-400" x-text="excerpt.length + '/180'"></span>
            </div>
            <textarea name="excerpt" x-model="excerpt" rows="3" maxlength="180"
                      placeholder="One or two sentences that make people want to read."
                      class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs font-medium text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none transition-all leading-relaxed"></textarea>
        </div>

        {{-- CARD 3: SEO --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-5">
            <div>
                <h3 class="text-xs font-black text-[#a88210] uppercase tracking-wider">SEARCH ENGINE OPTIMIZATION</h3>
                <p class="text-[11px] text-stone-400 mt-0.5">Same professional SEO approach used across the tour pages.</p>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-[11px] font-bold text-stone-600 uppercase tracking-wider">META TITLE</label>
                    <span class="text-[10px] font-mono font-bold text-stone-400" x-text="metaTitle.length + '/60'"></span>
                </div>
                <input type="text" name="meta_title" x-model="metaTitle" maxlength="255"
                       placeholder="e.g. Best Time for Tanzania Safari | Twina Safaris"
                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none">
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-[11px] font-bold text-stone-600 uppercase tracking-wider">META DESCRIPTION</label>
                    <span class="text-[10px] font-mono font-bold text-stone-400" x-text="metaDescription.length + '/155'"></span>
                </div>
                <textarea name="meta_description" x-model="metaDescription" rows="2" maxlength="320"
                          placeholder="A compelling description for Google search results."
                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs font-medium text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none leading-relaxed"></textarea>
            </div>
        </div>

        {{-- CARD 4: BLOCK BUILDER --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-xs font-black text-[#a88210] uppercase tracking-wider">FULL STORY CONTENT</h3>
                    <p class="text-[11px] text-stone-400 mt-0.5">Drag blocks to reorder. Add a square photo right after any paragraph, TourRadar style.</p>
                </div>
                <button type="button" @click="previewOpen = !previewOpen"
                        class="px-5 py-2.5 border-2 border-[#d4af37] text-[#8a6a0b] hover:bg-[#d4af37] hover:text-white font-black text-xs uppercase tracking-wider rounded-full transition-all">
                    <span x-text="previewOpen ? 'Hide Preview' : 'Live Preview'"></span>
                </button>
            </div>

            {{-- Live preview --}}
            <div x-show="previewOpen" x-cloak class="rounded-2xl border border-[#e8dfcf] bg-[#fdfbf7] p-5 md:p-8">
                <p class="text-[10px] font-black text-[#a88210] uppercase tracking-widest mb-4">Reader preview</p>
                <div class="bg-white rounded-2xl border border-stone-200 p-6 md:p-8 shadow-xs">
                    <h2 class="font-display text-2xl md:text-3xl font-black text-stone-900 mb-2" x-text="title || 'Untitled story'"></h2>
                    <p class="text-xs text-stone-400 font-semibold mb-6" x-text="excerpt"></p>
                    <div class="article-body-preview space-y-4 text-sm text-stone-700 leading-relaxed" x-html="generateHtmlContent()"></div>
                </div>
            </div>

            {{-- Blocks --}}
            <div class="space-y-4">
                <template x-for="(block, bIndex) in blocks" :key="block.id">
                    <div class="bg-white border-2 rounded-xl p-4 md:p-5 shadow-2xs transition-all"
                         :class="draggedIndex === bIndex ? 'border-[#d4af37] opacity-60' : 'border-[#e6c278]/70'"
                         draggable="true"
                         @dragstart="dragStart(bIndex, $event)"
                         @dragover.prevent="dragOver(bIndex, $event)"
                         @drop.prevent="dropBlock(bIndex)"
                         @dragend="dragEnd()">

                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-stone-100">
                            <div class="flex items-center gap-2">
                                <span class="cursor-grab text-stone-400 hover:text-[#d4af37] select-none" title="Drag to reorder">⠿</span>
                                <span class="text-[10px] font-black text-[#a88210] uppercase tracking-widest" x-text="blockLabels[block.type] || block.type"></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" x-show="bIndex > 0" @click="moveBlock(bIndex, -1)" title="Move up" class="w-6 h-6 border border-stone-300 hover:border-[#d4af37] hover:bg-[#fcfaf5] text-stone-600 rounded-md font-bold text-xs flex items-center justify-center">↑</button>
                                <button type="button" x-show="bIndex < blocks.length - 1" @click="moveBlock(bIndex, 1)" title="Move down" class="w-6 h-6 border border-stone-300 hover:border-[#d4af37] hover:bg-[#fcfaf5] text-stone-600 rounded-md font-bold text-xs flex items-center justify-center">↓</button>
                                <button type="button" @click="deleteBlock(bIndex)" title="Delete block" class="w-6 h-6 border border-rose-200 hover:border-rose-500 hover:bg-rose-50 text-rose-600 rounded-md font-bold text-xs flex items-center justify-center">×</button>
                            </div>
                        </div>

                        {{-- HEADING --}}
                        <template x-if="block.type === 'heading'">
                            <input type="text" x-model="block.content" placeholder="Section heading"
                                   class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-base font-serif-title font-bold text-stone-900 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                        </template>

                        {{-- PARAGRAPH --}}
                        <template x-if="block.type === 'paragraph'">
                            <div class="space-y-2">
                                <textarea x-model="block.content" rows="4" placeholder="Write the text for this section"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                                <button type="button" @click="insertPhotoAfter(bIndex)"
                                        class="text-[10px] font-black text-[#a88210] uppercase tracking-wider hover:text-[#d4af37] transition-all">+ Add square photo after this paragraph</button>
                            </div>
                        </template>

                        {{-- BULLET LIST --}}
                        <template x-if="block.type === 'bullet_list'">
                            <textarea x-model="block.content" rows="4" placeholder="One item per line"
                                      class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                        </template>

                        {{-- SQUARE PHOTO --}}
                        <template x-if="block.type === 'photo'">
                            <div class="space-y-3">
                                <input type="file" accept="image/*" :id="'blog_photo_input_' + bIndex" class="hidden" @change="uploadBlockPhoto($event, bIndex)">
                                <div @click="document.getElementById('blog_photo_input_' + bIndex).click()"
                                     class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-xl p-4 text-center cursor-pointer transition-all flex flex-col items-center justify-center relative">
                                    <template x-if="block.content">
                                        <div class="relative w-32 aspect-square overflow-hidden rounded-lg shadow-xs">
                                            <img :src="photoSrc(block.content)" class="w-full h-full object-cover" alt="Photo preview">
                                            <span class="absolute bottom-1 right-1 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-0.5 rounded">Change</span>
                                        </div>
                                    </template>
                                    <template x-if="!block.content">
                                        <div class="py-3">
                                            <svg class="w-6 h-6 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-[10px] font-black text-[#a88210] uppercase tracking-wider">CLICK TO UPLOAD SQUARE PHOTO</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="text" x-model="block.caption" placeholder="Caption (optional)"
                                           class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                    <input type="text" x-model="block.alt" placeholder="Alt text (accessibility)"
                                           class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                </div>
                            </div>
                        </template>

                        {{-- QUOTE --}}
                        <template x-if="block.type === 'quote'">
                            <div class="space-y-3">
                                <textarea x-model="block.content" rows="3" placeholder="A memorable quote from the story"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-sm font-serif-title italic text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none"></textarea>
                                <input type="text" x-model="block.caption" placeholder="Attribution (optional)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                            </div>
                        </template>

                        {{-- CALLOUT --}}
                        <template x-if="block.type === 'callout'">
                            <div class="space-y-3">
                                <input type="text" x-model="block.caption" placeholder="Callout title (e.g. Traveler Tip)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-black text-[#a88210] uppercase tracking-wider focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                <textarea x-model="block.content" rows="3" placeholder="Highlighted advice for the reader"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                            </div>
                        </template>

                        {{-- VIDEO --}}
                        <template x-if="block.type === 'video'">
                            <div class="space-y-3">
                                <input type="url" x-model="block.content" placeholder="https://www.youtube.com/watch?v=..."
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                <p class="text-[10px] font-semibold" :class="embedUrl(block.content) ? 'text-emerald-700' : 'text-stone-400'"
                                   x-text="embedUrl(block.content) ? 'Video ready to embed' : 'Paste a YouTube or Vimeo link'"></p>
                                <input type="text" x-model="block.caption" placeholder="Caption (optional)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Add block toolbar --}}
            <div class="pt-2 flex items-center gap-2 flex-wrap">
                <template x-for="type in ['heading', 'paragraph', 'photo', 'bullet_list', 'quote', 'callout', 'video']" :key="type">
                    <button type="button" @click="addBlock(type)"
                            class="px-4 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-[11px] uppercase tracking-wider rounded-full transition-all">
                        <span x-text="'+ ' + (blockLabels[type] || type)"></span>
                    </button>
                </template>
            </div>

            <style>
                .article-body-preview h2 { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.4rem; font-weight: 700; color: #0d2818; margin: 1.25rem 0 .5rem; }
                .article-body-preview ul { list-style: disc; padding-left: 1.25rem; margin: .75rem 0; }
                .article-body-preview li { margin-bottom: .25rem; }
                .article-body-preview figure.image { margin: 1rem 0; text-align: center; }
                .article-body-preview figure.image img { width: 100%; max-width: 260px; aspect-ratio: 1 / 1; object-fit: cover; border-radius: .75rem; }
                .article-body-preview figcaption { font-size: 10px; color: #78716c; font-style: italic; margin-top: .35rem; }
                .article-body-preview blockquote { border-left: 3px solid #d4af37; background: #fcfaf7; padding: .75rem 1rem; font-style: italic; border-radius: 0 .5rem .5rem 0; }
                .article-body-preview .article-callout { background: #fcfaf7; border: 1px solid #e8dfcf; border-radius: .75rem; padding: .75rem 1rem; }
                .article-body-preview .article-video iframe { width: 100%; aspect-ratio: 16 / 9; border: 0; border-radius: .75rem; }
            </style>
        </div>

        {{-- CARD 5: PUBLISH --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $editing ? $blog->is_published : true) ? 'checked' : '' }} class="w-4 h-4 text-[#d4af37] rounded border-stone-300 focus:ring-[#d4af37]">
                <span class="text-xs font-black text-stone-800 uppercase tracking-wider">PUBLISH IMMEDIATELY</span>
            </label>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-8 py-3.5 bg-[#d4af37] hover:bg-[#b8920d] text-stone-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-md transition-all">
                    {{ $editing ? 'Save Story Changes' : 'Launch This Story' }}
                </button>
                <a href="{{ route('admin.blog.index') }}" class="px-6 py-3.5 text-xs font-bold text-stone-500 hover:text-stone-800 uppercase tracking-wider">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
function blogStoryEditor(config) {
    return {
        title: config.title || '',
        slug: config.slug || '',
        excerpt: config.excerpt || '',
        metaTitle: config.metaTitle || '',
        metaDescription: config.metaDescription || '',
        tags: config.tags || '',
        coverPreview: config.cover || null,
        slugAuto: !config.slug,
        previewOpen: false,
        draggedIndex: null,
        blockLabels: {
            heading: 'Heading', paragraph: 'Paragraph', photo: 'Square Photo',
            bullet_list: 'Bullet List', quote: 'Quote', callout: 'Callout', video: 'Video'
        },
        blocks: [],

        init() {
            this.blocks = this.parseHtmlToBlocks(config.content);
        },

        updateSlug() {
            if (!this.slug || this.slugAuto) {
                this.slug = this.title.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }
        },

        previewCover(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => this.coverPreview = e.target.result;
            reader.readAsDataURL(file);
        },

        addBlock(type) {
            const defaults = {
                heading: 'Section heading',
                paragraph: '',
                photo: '',
                bullet_list: '',
                quote: '',
                callout: '',
                video: ''
            };
            this.blocks.push({ id: Date.now() + Math.random(), type: type, content: defaults[type] || '', caption: '', alt: '' });
        },

        insertPhotoAfter(index) {
            this.blocks.splice(index + 1, 0, {
                id: Date.now() + Math.random(), type: 'photo', content: '', caption: '', alt: ''
            });
        },

        deleteBlock(index) {
            if (this.blocks.length > 1) this.blocks.splice(index, 1);
        },

        moveBlock(index, direction) {
            const target = index + direction;
            if (target >= 0 && target < this.blocks.length) {
                const temp = this.blocks[index];
                this.blocks[index] = this.blocks[target];
                this.blocks[target] = temp;
            }
        },

        dragStart(index, event) {
            this.draggedIndex = index;
            if (event.dataTransfer) {
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', String(index));
            }
        },

        dragOver(index, event) {
            if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
        },

        dropBlock(index) {
            if (this.draggedIndex === null || this.draggedIndex === index) return;
            const moved = this.blocks.splice(this.draggedIndex, 1)[0];
            this.blocks.splice(index, 0, moved);
            this.draggedIndex = null;
        },

        dragEnd() {
            this.draggedIndex = null;
        },

        uploadBlockPhoto(event, bIndex) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', config.csrf);

            fetch(config.uploadUrl, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.url) {
                        this.blocks[bIndex].content = data.url;
                        if (!this.blocks[bIndex].alt) {
                            this.blocks[bIndex].alt = file.name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' ');
                        }
                    } else {
                        const reader = new FileReader();
                        reader.onload = e => this.blocks[bIndex].content = e.target.result;
                        reader.readAsDataURL(file);
                    }
                })
                .catch(() => {
                    const reader = new FileReader();
                    reader.onload = e => this.blocks[bIndex].content = e.target.result;
                    reader.readAsDataURL(file);
                });
        },

        photoSrc(src) {
            if (!src) return '';
            if (src.startsWith('storage/')) return '/' + src;
            return src;
        },

        embedUrl(url) {
            if (!url) return '';
            const value = String(url);
            let match = value.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]{6,})/);
            if (match) return 'https://www.youtube-nocookie.com/embed/' + match[1];
            match = value.match(/vimeo\.com\/(?:video\/)?(\d+)/);
            if (match) return 'https://player.vimeo.com/video/' + match[1];
            return '';
        },

        escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        generateHtmlContent() {
            let html = '';
            this.blocks.forEach(block => {
                const content = (block.content || '').trim();
                const caption = (block.caption || '').trim();
                const alt = (block.alt || caption).trim();

                if (!content && block.type !== 'photo') return;

                if (block.type === 'heading') {
                    html += `<h2>${this.escapeHtml(content)}</h2>\n`;
                } else if (block.type === 'paragraph') {
                    content.split(/\n\s*\n/).forEach(p => {
                        if (p.trim()) html += `<p>${this.escapeHtml(p.trim()).replace(/\n/g, '<br>')}</p>\n`;
                    });
                } else if (block.type === 'bullet_list') {
                    const lines = content.split('\n').map(l => l.trim()).filter(l => l).map(l => l.replace(/^[•\-\*]\s*/, ''));
                    if (lines.length) {
                        html += '<ul class="article-list">\n';
                        lines.forEach(line => { html += `  <li>${this.escapeHtml(line)}</li>\n`; });
                        html += '</ul>\n';
                    }
                } else if (block.type === 'photo') {
                    const src = this.photoSrc(content);
                    if (!src) return;
                    html += '<figure class="image article-photo">\n';
                    html += `  <img src="${this.escapeHtml(src)}" alt="${this.escapeHtml(alt)}" loading="lazy" decoding="async">\n`;
                    if (caption) html += `  <figcaption>${this.escapeHtml(caption)}</figcaption>\n`;
                    html += '</figure>\n';
                } else if (block.type === 'quote') {
                    html += '<blockquote class="article-quote">\n';
                    html += `  <p>${this.escapeHtml(content)}</p>\n`;
                    if (caption) html += `  <footer>${this.escapeHtml(caption)}</footer>\n`;
                    html += '</blockquote>\n';
                } else if (block.type === 'callout') {
                    html += '<div class="article-callout">\n';
                    if (caption) html += `  <strong>${this.escapeHtml(caption)}</strong>\n`;
                    html += `  <p>${this.escapeHtml(content)}</p>\n`;
                    html += '</div>\n';
                } else if (block.type === 'video') {
                    const embed = this.embedUrl(content);
                    if (!embed) return;
                    html += '<div class="article-video">\n';
                    html += `  <iframe src="${this.escapeHtml(embed)}" title="${this.escapeHtml(caption || 'Safari video')}" loading="lazy" allowfullscreen></iframe>\n`;
                    if (caption) html += `  <p><em>${this.escapeHtml(caption)}</em></p>\n`;
                    html += '</div>\n';
                }
            });
            return html;
        },

        parseHtmlToBlocks(html) {
            if (!html || !html.trim()) {
                return [
                    { id: 1, type: 'heading', content: 'Introduction', caption: '', alt: '' },
                    { id: 2, type: 'paragraph', content: '', caption: '', alt: '' }
                ];
            }

            const temp = document.createElement('div');
            temp.innerHTML = html;
            const parsed = [];
            let id = 1;

            Array.from(temp.children).forEach(node => {
                const tag = node.tagName ? node.tagName.toLowerCase() : '';
                const caption = (node.querySelector('figcaption')?.textContent || '').trim();
                const img = node.querySelector('img');

                if (['h1', 'h2', 'h3', 'h4'].includes(tag)) {
                    parsed.push({ id: id++, type: 'heading', content: node.textContent.trim(), caption: '', alt: '' });
                } else if (tag === 'p') {
                    const text = node.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/<[^>]*>/g, '').trim();
                    if (text) {
                        parsed.push({ id: id++, type: 'paragraph', content: text, caption: '', alt: '' });
                    }
                    if (img) {
                        parsed.push({ id: id++, type: 'photo', content: img.getAttribute('src') || '', caption: '', alt: img.getAttribute('alt') || '' });
                    }
                } else if (tag === 'ul' || tag === 'ol') {
                    const items = Array.from(node.querySelectorAll('li')).map(li => li.textContent.trim()).filter(Boolean);
                    if (items.length) parsed.push({ id: id++, type: 'bullet_list', content: items.join('\n'), caption: '', alt: '' });
                } else if (tag === 'figure') {
                    if (img) {
                        parsed.push({ id: id++, type: 'photo', content: img.getAttribute('src') || '', caption: caption, alt: img.getAttribute('alt') || '' });
                    }
                } else if (tag === 'img') {
                    parsed.push({ id: id++, type: 'photo', content: node.getAttribute('src') || '', caption: '', alt: node.getAttribute('alt') || '' });
                } else if (tag === 'blockquote') {
                    const quoteText = node.querySelector('p')?.textContent || node.textContent;
                    parsed.push({ id: id++, type: 'quote', content: quoteText.trim(), caption: (node.querySelector('footer')?.textContent || '').trim(), alt: '' });
                } else if (tag === 'div' && node.classList.contains('article-callout')) {
                    parsed.push({ id: id++, type: 'callout', content: (node.querySelector('p')?.textContent || node.textContent).trim(), caption: (node.querySelector('strong')?.textContent || '').trim(), alt: '' });
                } else if (tag === 'div' && node.classList.contains('article-video')) {
                    const iframe = node.querySelector('iframe');
                    parsed.push({ id: id++, type: 'video', content: iframe ? (iframe.getAttribute('src') || '') : '', caption: (node.querySelector('p')?.textContent || '').trim(), alt: '' });
                }
            });

            return parsed.length ? parsed : [
                { id: 1, type: 'heading', content: 'Story Content', caption: '', alt: '' },
                { id: 2, type: 'paragraph', content: html.replace(/<[^>]*>/g, '').trim(), caption: '', alt: '' }
            ];
        }
    }
}
</script>
