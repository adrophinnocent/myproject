@php
    $editing = ! empty($blog);
    $editorConfig = [
        'title' => old('title', $blog->title ?? ''),
        'slug' => old('slug', $blog->slug ?? ''),
        'excerpt' => old('excerpt', $blog->excerpt ?? ''),
        'heroCaption' => old('hero_caption', $blog->hero_caption ?? ''),
        'metaTitle' => old('meta_title', $blog->meta_title ?? ''),
        'metaDescription' => old('meta_description', $blog->meta_description ?? ''),
        'tags' => old('tags', $editing ? implode(', ', (array) ($blog->tags ?? [])) : ''),
        'cover' => $editing ? ($blog->featured_image_url ?? '') : '',
        'content' => old('content', $blog->content ?? ''),
        'uploadUrl' => route('admin.blog.upload-image'),
        'csrf' => csrf_token(),
    ];
@endphp

{{-- Config lives in a script tag so JSON quotes never break an HTML attribute. --}}
<script>
    window.blogStoryEditorConfig = @json($editorConfig);
</script>

<div class="max-w-5xl mx-auto pb-16" x-data="blogStoryEditor(window.blogStoryEditorConfig)">
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

        {{-- Generated HTML is submitted as one field; the blocks above are the source of truth. --}}
        <input type="hidden" name="content" :value="generateHtmlContent()">

        {{-- CARD 1: STORY META --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-6">
            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">STORY TITLE *</label>
                <input type="text" name="title" x-model="title" @input="updateSlug()" required
                       placeholder="e.g. Kilimanjaro Safety Tips Every Climber Should Know"
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
                        <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">HERO IMAGE</label>
                        <span class="text-[10px] font-bold text-stone-400">3:2 · min 1600px wide · WebP</span>
                    </div>
                    <input type="file" name="featured_image" id="cover_image_input" accept="image/*" class="hidden" @change="previewCover($event)" @if(!$editing) required @endif>
                    <div @click="document.getElementById('cover_image_input').click()"
                         class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-2xl p-5 text-center cursor-pointer transition-all flex flex-col items-center justify-center min-h-[110px] relative overflow-hidden">
                        <template x-if="coverPreview">
                            <div class="w-full aspect-[3/2] relative overflow-hidden rounded-xl">
                                <img :src="coverPreview" class="w-full h-full object-cover" alt="Hero preview">
                                <span class="absolute bottom-1 right-1 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-0.5 rounded">Change hero</span>
                            </div>
                        </template>
                        <template x-if="!coverPreview">
                            <div>
                                <svg class="w-7 h-7 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-black text-[#a88210] uppercase tracking-wider">CLICK TO UPLOAD HERO</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">HERO CAPTION</label>
                <input type="text" name="hero_caption" x-model="heroCaption" maxlength="255"
                       placeholder="e.g. Sunrise over the Kilimanjaro summit"
                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-xs font-semibold text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none">
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
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">SHORT INTRO (EXCERPT)</label>
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
                       placeholder="e.g. Kilimanjaro Safety Tips | Twina Safaris"
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
                    <p class="text-[11px] text-stone-400 mt-0.5">Drag to reorder, or use the arrows. Every photo is automatically cropped to 3:2.</p>
                </div>
                <button type="button" @click="previewOpen = !previewOpen"
                        class="px-5 py-2.5 border-2 border-[#d4af37] text-[#8a6a0b] hover:bg-[#d4af37] hover:text-white font-black text-xs uppercase tracking-wider rounded-full transition-all">
                    <span x-text="previewOpen ? 'Hide Preview' : 'Live Preview'"></span>
                </button>
            </div>

            {{-- Live preview --}}
            <div x-show="previewOpen" x-cloak class="rounded-2xl border border-[#e8dfcf] bg-[#fdfbf7] p-5 md:p-8">
                <p class="text-[10px] font-black text-[#a88210] uppercase tracking-widest mb-4">Reader preview</p>
                <div class="bg-white rounded-2xl border border-stone-200 p-6 md:p-8 shadow-xs max-w-[760px] mx-auto">
                    <h2 class="font-display text-2xl md:text-3xl font-black text-stone-900 mb-2" x-text="title || 'Untitled story'"></h2>
                    <p class="text-xs text-stone-400 font-semibold mb-6" x-text="excerpt"></p>
                    <div class="article-body-preview" x-html="generateHtmlContent()"></div>
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

                        {{-- HEADINGS --}}
                        <template x-if="block.type === 'heading_lg' || block.type === 'heading_sm'">
                            <input type="text" x-model="block.content" placeholder="Section heading"
                                   class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 font-serif-title font-bold text-stone-900 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                        </template>

                        {{-- PARAGRAPH --}}
                        <template x-if="block.type === 'paragraph'">
                            <textarea x-model="block.content" rows="4" placeholder="Write the text for this section"
                                      class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                        </template>

                        {{-- CHECK LIST / BULLET LIST / STEPS --}}
                        <template x-if="block.type === 'check_list' || block.type === 'bullet_list' || block.type === 'steps'">
                            <div class="space-y-1">
                                <textarea x-model="block.content" rows="4" placeholder="One item per line"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                                <p class="text-[10px] text-stone-400 font-semibold">One item per line · 2 columns on desktop, 1 on mobile</p>
                            </div>
                        </template>

                        {{-- SQUARE-FREE 3:2 PHOTO --}}
                        <template x-if="block.type === 'photo'">
                            <div class="space-y-3">
                                <input type="file" accept="image/*" :id="'blog_photo_input_' + bIndex" class="hidden" @change="uploadBlockPhoto($event, bIndex)">
                                <div @click="document.getElementById('blog_photo_input_' + bIndex).click()"
                                     class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-xl p-4 text-center cursor-pointer transition-all flex flex-col items-center justify-center relative">
                                    <template x-if="block.content">
                                        <div class="w-40 aspect-[3/2] relative overflow-hidden rounded-lg shadow-xs">
                                            <img :src="photoSrc(block.content)" class="w-full h-full object-cover" alt="Photo preview">
                                            <span class="absolute bottom-1 right-1 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-0.5 rounded">Change</span>
                                        </div>
                                    </template>
                                    <template x-if="!block.content">
                                        <div class="py-3">
                                            <svg class="w-6 h-6 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-[10px] font-black text-[#a88210] uppercase tracking-wider">CLICK TO UPLOAD · AUTO 3:2 · 1200×800 WEBP</span>
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
                                <textarea x-model="block.content" rows="3" placeholder="A memorable quote, e.g. Pole Pole"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-sm font-serif-title italic text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none"></textarea>
                                <input type="text" x-model="block.caption" placeholder="Attribution (optional)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                            </div>
                        </template>

                        {{-- NOTE: TIP / WARNING / INFO --}}
                        <template x-if="block.type === 'note'">
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <select x-model="block.variant" class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-bold text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                        <option value="tip">Kidokezo (Tip)</option>
                                        <option value="warning">Tahadhari (Warning)</option>
                                        <option value="info">Taarifa (Info)</option>
                                    </select>
                                    <input type="text" x-model="block.caption" placeholder="Box title (optional)"
                                           class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                </div>
                                <textarea x-model="block.content" rows="3" placeholder="Highlighted advice for the reader"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                            </div>
                        </template>

                        {{-- FAQ --}}
                        <template x-if="block.type === 'faq'">
                            <div class="space-y-3">
                                <input type="text" x-model="block.caption" placeholder="Swali (question)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-bold text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                <textarea x-model="block.content" rows="3" placeholder="Jibu (answer)"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                                <p class="text-[10px] text-stone-400 font-semibold">Readers can open and close this answer.</p>
                            </div>
                        </template>

                        {{-- CTA --}}
                        <template x-if="block.type === 'cta'">
                            <div class="space-y-3">
                                <input type="text" x-model="block.title" placeholder="CTA heading"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-bold text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                <textarea x-model="block.content" rows="2" placeholder="Short description"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="text" x-model="block.buttonText" placeholder="Button label (e.g. Plan My Safari)"
                                           class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                    <input type="text" x-model="block.url" placeholder="Button link (/tours ...)"
                                           class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                                </div>
                            </div>
                        </template>

                        {{-- DIVIDER --}}
                        <template x-if="block.type === 'divider'">
                            <p class="text-center text-[#d4af37] tracking-[.5em] text-sm py-2">✦ ✦ ✦</p>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Add block toolbar --}}
            <div class="pt-2 border-t border-stone-100">
                <p class="text-[10px] font-black text-stone-400 uppercase tracking-wider mb-3">Add a block</p>
                <div class="flex items-center gap-2 flex-wrap">
                    <template x-for="type in blockTypes" :key="type">
                        <button type="button" @click="addBlock(type)"
                                class="px-3.5 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-[10px] uppercase tracking-wider rounded-full transition-all">
                            <span x-text="'+ ' + (blockLabels[type] || type)"></span>
                        </button>
                    </template>
                </div>
            </div>

            <style>
                .article-body-preview h2, .article-body-preview h3 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #0d2818; }
                .article-body-preview h2 { font-size: 1.5rem; margin: 1.25rem 0 .5rem; border-bottom: 2px solid #f1f0ea; padding-bottom: .4rem; }
                .article-body-preview h3 { font-size: 1.2rem; margin: 1rem 0 .5rem; }
                .article-body-preview p { margin-bottom: .8rem; font-size: .85rem; line-height: 1.7; }
                .article-body-preview ul, .article-body-preview ol { padding-left: 1.25rem; margin: .75rem 0; font-size: .85rem; }
                .article-body-preview li { margin-bottom: .25rem; }
                .article-body-preview .article-check-list, .article-body-preview .article-bullet-list { list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: .35rem 1rem; }
                .article-body-preview .article-check-list li::before { content: '✓'; color: #d4af37; margin-right: .5rem; font-weight: 800; }
                .article-body-preview .article-bullet-list li::before { content: '•'; color: #8a6a0b; margin-right: .5rem; font-weight: 800; }
                .article-body-preview .article-steps { list-style: none; padding: 0; }
                .article-body-preview .article-steps li { padding-left: 2.5rem; position: relative; }
                .article-body-preview .article-step-num { position: absolute; left: 0; top: -.1rem; width: 1.7rem; height: 1.7rem; border-radius: 9999px; background: #d4af37; color: #1a1209; font-weight: 800; font-size: .7rem; display: grid; place-items: center; }
                .article-body-preview figure.image { margin: 1rem 0; text-align: center; background: #faf9f6; border: 1px solid #e7e5e4; padding: .35rem; border-radius: 1rem; }
                .article-body-preview figure.image img { width: 100%; aspect-ratio: 3 / 2; object-fit: cover; border-radius: .7rem; }
                .article-body-preview figcaption { font-size: .65rem; color: #78716c; font-style: italic; margin-top: .35rem; }
                .article-body-preview .article-quote { border-left: 3px solid #d4af37; background: #fcfaf7; padding: .75rem 1rem; font-style: italic; border-radius: 0 .5rem .5rem 0; }
                .article-body-preview .article-note { border-radius: .75rem; padding: .75rem 1rem; border-left: 4px solid; font-size: .8rem; }
                .article-body-preview .article-note strong { display: block; font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; }
                .article-body-preview .article-note--tip { background: #f0fdf4; border-color: #86efac; color: #14532d; }
                .article-body-preview .article-note--warning { background: #fffbeb; border-color: #fcd34d; color: #78350f; }
                .article-body-preview .article-note--info { background: #eff6ff; border-color: #93c5fd; color: #1e3a8a; }
                .article-body-preview .article-faq { border: 1px solid #e7e5e4; border-radius: .75rem; padding: .6rem .9rem; margin: .6rem 0; font-size: .8rem; }
                .article-body-preview .article-cta { background: #0d2818; color: white; border-radius: 1rem; padding: 1rem; text-align: center; }
                .article-body-preview .article-cta h3 { color: white; }
                .article-body-preview .article-cta a { display: inline-block; background: #d4af37; color: #1a1209; border-radius: 9999px; padding: .5rem 1rem; font-size: .65rem; font-weight: 800; text-decoration: none; }
                .article-body-preview .article-divider { text-align: center; color: #d4af37; letter-spacing: .5em; }
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
        heroCaption: config.heroCaption || '',
        metaTitle: config.metaTitle || '',
        metaDescription: config.metaDescription || '',
        coverPreview: config.cover || null,
        slugAuto: !config.slug,
        previewOpen: false,
        draggedIndex: null,
        blockTypes: ['heading_lg', 'heading_sm', 'paragraph', 'check_list', 'bullet_list', 'steps', 'photo', 'quote', 'note', 'faq', 'cta', 'divider'],
        blockLabels: {
            heading_lg: 'Kichwa Kikubwa', heading_sm: 'Kichwa Kidogo', paragraph: 'Aya',
            check_list: 'Orodha ya ✓', bullet_list: 'Orodha ya •', steps: 'Hatua 1, 2, 3',
            photo: 'Picha 3:2', quote: 'Nukuu Kubwa', note: 'Kidokezo / Tahadhari / Taarifa',
            faq: 'Swali na Jibu', cta: 'Kisanduku cha Kitufe', divider: 'Mstari wa Mapambo'
        },
        noteLabels: { tip: 'Kidokezo', warning: 'Tahadhari', info: 'Taarifa' },
        blocks: [],

        init() {
            this.blocks = this.parseHtmlToBlocks(config.content);
        },

        newBlock(type) {
            return {
                id: Date.now() + Math.random(),
                type: type,
                content: '',
                caption: '',
                alt: '',
                variant: 'tip',
                title: '',
                buttonText: 'Read More',
                url: '/blog'
            };
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
            const block = this.newBlock(type);
            if (type === 'heading_lg') block.content = 'Section heading';
            this.blocks.push(block);
        },

        deleteBlock(index) {
            this.blocks.splice(index, 1);
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

        safeUrl(url) {
            const value = String(url || '').trim();
            if (/^(https?:\/\/|\/)/i.test(value)) return value;
            return '/blog';
        },

        embedUrl(url) {
            return this.safeUrl(url);
        },

        escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        listItems(content) {
            return String(content || '').split('\n')
                .map(line => line.trim().replace(/^[✓•\-\*]\s*/, ''))
                .filter(line => line);
        },

        generateHtmlContent() {
            let html = '';
            this.blocks.forEach(block => {
                const content = (block.content || '').trim();
                const caption = (block.caption || '').trim();
                const alt = (block.alt || caption).trim();

                if (block.type === 'heading_lg') {
                    html += `<h2 class="article-heading-lg">${this.escapeHtml(content || 'Section heading')}</h2>\n`;
                } else if (block.type === 'heading_sm') {
                    html += `<h3 class="article-heading-sm">${this.escapeHtml(content || 'Section heading')}</h3>\n`;
                } else if (block.type === 'paragraph') {
                    content.split(/\n\s*\n/).forEach(p => {
                        if (p.trim()) html += `<p>${this.escapeHtml(p.trim()).replace(/\n/g, '<br>')}</p>\n`;
                    });
                } else if (block.type === 'check_list' || block.type === 'bullet_list') {
                    const items = this.listItems(content);
                    if (items.length) {
                        html += `<ul class="article-${block.type === 'check_list' ? 'check' : 'bullet'}-list">\n`;
                        items.forEach(item => { html += `  <li>${this.escapeHtml(item)}</li>\n`; });
                        html += '</ul>\n';
                    }
                } else if (block.type === 'steps') {
                    const items = this.listItems(content);
                    if (items.length) {
                        html += '<ol class="article-steps">\n';
                        items.forEach((item, i) => {
                            html += `  <li><span class="article-step-num">${i + 1}</span>${this.escapeHtml(item)}</li>\n`;
                        });
                        html += '</ol>\n';
                    }
                } else if (block.type === 'photo') {
                    const src = this.photoSrc(content);
                    if (!src) return;
                    html += '<figure class="image article-photo">\n';
                    html += `  <img src="${this.escapeHtml(src)}" alt="${this.escapeHtml(alt)}" loading="lazy" decoding="async">\n`;
                    if (caption) html += `  <figcaption>${this.escapeHtml(caption)}</figcaption>\n`;
                    html += '</figure>\n';
                } else if (block.type === 'quote') {
                    if (!content) return;
                    html += '<blockquote class="article-quote">\n';
                    html += `  <p>${this.escapeHtml(content)}</p>\n`;
                    if (caption) html += `  <footer>${this.escapeHtml(caption)}</footer>\n`;
                    html += '</blockquote>\n';
                } else if (block.type === 'note') {
                    if (!content) return;
                    const variant = ['tip', 'warning', 'info'].includes(block.variant) ? block.variant : 'tip';
                    html += `<div class="article-note article-note--${variant}">\n`;
                    html += `  <strong>${this.escapeHtml(caption || this.noteLabels[variant])}</strong>\n`;
                    html += `  <p>${this.escapeHtml(content).replace(/\n/g, '<br>')}</p>\n`;
                    html += '</div>\n';
                } else if (block.type === 'faq') {
                    if (!caption && !content) return;
                    html += '<details class="article-faq">\n';
                    html += `  <summary>${this.escapeHtml(caption || 'Swali')}</summary>\n`;
                    html += `  <div class="article-faq-answer"><p>${this.escapeHtml(content).replace(/\n/g, '<br>')}</p></div>\n`;
                    html += '</details>\n';
                } else if (block.type === 'cta') {
                    html += '<div class="article-cta">\n';
                    if (block.title) html += `  <h3>${this.escapeHtml(block.title)}</h3>\n`;
                    if (content) html += `  <p>${this.escapeHtml(content)}</p>\n`;
                    html += `  <a class="btn-gold" href="${this.escapeHtml(this.safeUrl(block.url))}">${this.escapeHtml(block.buttonText || 'Read More')}</a>\n`;
                    html += '</div>\n';
                } else if (block.type === 'divider') {
                    html += '<div class="article-divider">✦ ✦ ✦</div>\n';
                }
            });
            return html;
        },

        parseHtmlToBlocks(html) {
            if (!html || !html.trim()) {
                return [
                    Object.assign(this.newBlock('heading_lg'), { content: 'Introduction' }),
                    this.newBlock('paragraph')
                ];
            }

            const temp = document.createElement('div');
            temp.innerHTML = html;
            const parsed = [];

            Array.from(temp.children).forEach(node => {
                const tag = node.tagName ? node.tagName.toLowerCase() : '';
                const classes = String(node.className || '');
                const img = node.querySelector('img');
                const caption = (node.querySelector('figcaption')?.textContent || '').trim();
                const text = node.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/<[^>]*>/g, '').trim();

                if (['h1', 'h2'].includes(tag)) {
                    parsed.push(Object.assign(this.newBlock('heading_lg'), { content: node.textContent.trim() }));
                } else if (['h3', 'h4'].includes(tag)) {
                    parsed.push(Object.assign(this.newBlock('heading_sm'), { content: node.textContent.trim() }));
                } else if (tag === 'p') {
                    if (text) parsed.push(Object.assign(this.newBlock('paragraph'), { content: text }));
                    if (img) parsed.push(Object.assign(this.newBlock('photo'), {
                        content: img.getAttribute('src') || '', alt: img.getAttribute('alt') || ''
                    }));
                } else if (tag === 'ul' && classes.includes('article-check-list')) {
                    const items = Array.from(node.querySelectorAll('li')).map(li => li.textContent.trim()).filter(Boolean);
                    parsed.push(Object.assign(this.newBlock('check_list'), { content: items.join('\n') }));
                } else if (tag === 'ul' && classes.includes('article-bullet-list')) {
                    const items = Array.from(node.querySelectorAll('li')).map(li => li.textContent.trim()).filter(Boolean);
                    parsed.push(Object.assign(this.newBlock('bullet_list'), { content: items.join('\n') }));
                } else if (tag === 'ol' && classes.includes('article-steps')) {
                    const items = Array.from(node.querySelectorAll('li')).map(li => {
                        const clone = li.cloneNode(true);
                        const num = clone.querySelector('.article-step-num');
                        if (num) num.remove();
                        return clone.textContent.trim();
                    }).filter(Boolean);
                    parsed.push(Object.assign(this.newBlock('steps'), { content: items.join('\n') }));
                } else if (tag === 'ul' || tag === 'ol') {
                    const items = Array.from(node.querySelectorAll('li')).map(li => li.textContent.trim()).filter(Boolean);
                    parsed.push(Object.assign(this.newBlock('bullet_list'), { content: items.join('\n') }));
                } else if (tag === 'figure' || tag === 'img') {
                    const source = img || node;
                    parsed.push(Object.assign(this.newBlock('photo'), {
                        content: source.getAttribute('src') || '', caption: caption, alt: source.getAttribute('alt') || ''
                    }));
                } else if (tag === 'blockquote') {
                    parsed.push(Object.assign(this.newBlock('quote'), {
                        content: (node.querySelector('p')?.textContent || node.textContent).trim(),
                        caption: (node.querySelector('footer')?.textContent || '').trim()
                    }));
                } else if (tag === 'div' && classes.includes('article-note')) {
                    let variant = 'tip';
                    if (classes.includes('warning')) variant = 'warning';
                    if (classes.includes('info')) variant = 'info';
                    parsed.push(Object.assign(this.newBlock('note'), {
                        variant: variant,
                        caption: (node.querySelector('strong')?.textContent || '').trim(),
                        content: (node.querySelector('p')?.textContent || node.textContent).trim()
                    }));
                } else if (tag === 'details' && classes.includes('article-faq')) {
                    parsed.push(Object.assign(this.newBlock('faq'), {
                        caption: (node.querySelector('summary')?.textContent || '').trim(),
                        content: (node.querySelector('.article-faq-answer')?.textContent || '').trim()
                    }));
                } else if (tag === 'div' && classes.includes('article-cta')) {
                    const link = node.querySelector('a');
                    parsed.push(Object.assign(this.newBlock('cta'), {
                        title: (node.querySelector('h3')?.textContent || '').trim(),
                        content: (node.querySelector('p')?.textContent || '').trim(),
                        buttonText: link ? link.textContent.trim() : 'Read More',
                        url: link ? (link.getAttribute('href') || '/blog') : '/blog'
                    }));
                } else if (tag === 'div' && classes.includes('article-divider')) {
                    parsed.push(this.newBlock('divider'));
                }
            });

            if (!parsed.length && html.trim()) {
                parsed.push(Object.assign(this.newBlock('paragraph'), { content: html.replace(/<[^>]*>/g, '').trim() }));
            }

            return parsed;
        }
    }
}
</script>
