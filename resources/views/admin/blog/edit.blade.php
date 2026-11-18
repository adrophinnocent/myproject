@extends('admin.layouts.app')

@section('title', 'Edit Story')
@section('page-title', 'Edit Story: ' . $blog->title)

@section('content')
<div class="max-w-4xl mx-auto pb-16" x-data="blogEditStoryForm()">

    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-serif-title font-bold text-stone-900">Edit Story</h1>
            <p class="text-xs text-stone-500 font-medium">Update story content, cover photo, or publication status.</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="px-4 py-2 bg-white border border-stone-200 rounded-xl text-xs font-bold text-stone-600 uppercase">Back to Stories</a>
    </div>

    <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hidden Input for Generated HTML Content --}}
        <input type="hidden" name="content" :value="generateHtmlContent()">

        {{-- CARD 1: STORY META DETAILS --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-6">
            {{-- Story Title --}}
            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">STORY TITLE *</label>
                <input type="text"
                       id="blog-title"
                       name="title"
                       x-model="title"
                       required
                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-sm font-bold text-stone-900 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none transition-all">
            </div>

            {{-- URL Slug --}}
            <div>
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">URL SLUG</label>
                <div class="flex items-center bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-[#d4af37]/30 focus-within:border-[#d4af37]">
                    <span class="px-3.5 py-3 text-xs font-bold text-stone-500 bg-[#f4ebd9] border-r border-[#e2d5bd] shrink-0">twinasafaris.com/blog/</span>
                    <input type="text"
                           id="blog-slug"
                           name="slug"
                           x-model="slug"
                           class="w-full bg-transparent px-3 py-3 text-xs font-mono font-medium text-stone-800 outline-none">
                </div>
            </div>

            {{-- Category & Cover Image Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider mb-2">CATEGORY *</label>
                    <select name="category_id" required class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-xs font-bold text-stone-900 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none cursor-pointer">
                        <option value="">Choose a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">COVER IMAGE</label>
                        <span class="text-[10px] font-bold text-stone-400">1200x800px</span>
                    </div>

                    <input type="file"
                           name="featured_image"
                           id="edit_cover_image_input"
                           accept="image/*"
                           class="hidden"
                           @change="previewCover($event)">

                    <div @click="document.getElementById('edit_cover_image_input').click()"
                         class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-2xl p-4 text-center cursor-pointer transition-all flex flex-col items-center justify-center min-h-[110px] relative group overflow-hidden">
                        <template x-if="coverPreview">
                            <div class="w-full h-28 relative">
                                <img :src="coverPreview" class="w-full h-full object-cover rounded-xl shadow-xs">
                                <span class="absolute bottom-1 right-1 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-0.5 rounded">Change</span>
                            </div>
                        </template>
                        <template x-if="!coverPreview">
                            <div>
                                <svg class="w-7 h-7 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-black text-[#a88210] uppercase tracking-wider">CLICK TO CHOOSE A PHOTO</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: SHORT SUMMARY (EXCERPT) --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-black text-[#a88210] uppercase tracking-wider">
                    SHORT SUMMARY (EXCERPT) <span class="normal-case font-normal text-stone-400">shown on the blog list and Google</span>
                </label>
                <span class="text-[10px] font-mono font-bold text-stone-400" x-text="excerpt.length + '/180'"></span>
            </div>
            <textarea name="excerpt"
                      x-model="excerpt"
                      rows="3"
                      maxlength="180"
                      placeholder="One or two sentences that make people want to read."
                      class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs font-medium text-stone-800 focus:bg-white focus:ring-2 focus:ring-[#d4af37]/30 focus:border-[#d4af37] outline-none transition-all leading-relaxed"></textarea>
        </div>

        {{-- CARD 3: FULL STORY CONTENT (BLOCK BUILDER) --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 md:p-8 shadow-xs mb-6 space-y-6">
            <div>
                <h3 class="text-xs font-black text-[#a88210] uppercase tracking-wider">
                    FULL STORY CONTENT <span class="normal-case font-normal text-stone-400">add a heading, text, then a photo below it</span>
                </h3>
            </div>

            {{-- Dynamic Content Blocks --}}
            <div class="space-y-5">
                <template x-for="(block, bIndex) in blocks" :key="block.id">
                    <div class="bg-white border-2 border-[#e6c278]/60 rounded-xl p-4 md:p-5 shadow-2xs relative">

                        {{-- Block Header Bar --}}
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-stone-100">
                            <span class="text-[10px] font-black text-[#a88210] uppercase tracking-widest" x-text="block.type.replace('_', ' ')"></span>

                            {{-- Action Controls --}}
                            <div class="flex items-center gap-1">
                                <button type="button"
                                        x-show="bIndex > 0"
                                        @click="moveBlock(bIndex, -1)"
                                        title="Move Up"
                                        class="w-6 h-6 border border-stone-300 hover:border-[#d4af37] hover:bg-[#fcfaf5] text-stone-600 rounded-md font-bold text-xs flex items-center justify-center transition-all">
                                    ↑
                                </button>
                                <button type="button"
                                        x-show="bIndex < blocks.length - 1"
                                        @click="moveBlock(bIndex, 1)"
                                        title="Move Down"
                                        class="w-6 h-6 border border-stone-300 hover:border-[#d4af37] hover:bg-[#fcfaf5] text-stone-600 rounded-md font-bold text-xs flex items-center justify-center transition-all">
                                    ↓
                                </button>
                                <button type="button"
                                        @click="deleteBlock(bIndex)"
                                        title="Delete Block"
                                        class="w-6 h-6 border border-rose-200 hover:border-rose-500 hover:bg-rose-50 text-rose-600 rounded-md font-bold text-xs flex items-center justify-center transition-all">
                                    ×
                                </button>
                            </div>
                        </div>

                        {{-- HEADING BLOCK --}}
                        <template x-if="block.type === 'heading'">
                            <div>
                                <input type="text"
                                       x-model="block.content"
                                       placeholder="Heading Title"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-3 text-base font-serif-title font-bold text-stone-900 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                            </div>
                        </template>

                        {{-- PARAGRAPH BLOCK --}}
                        <template x-if="block.type === 'paragraph'">
                            <div>
                                <textarea x-model="block.content"
                                          rows="4"
                                          placeholder="Write the text for this section"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs font-normal text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                            </div>
                        </template>

                        {{-- BULLET LIST BLOCK --}}
                        <template x-if="block.type === 'bullet_list'">
                            <div>
                                <textarea x-model="block.content"
                                          rows="4"
                                          placeholder="Enter each bullet item on a new line"
                                          class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl p-4 text-xs font-normal text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none leading-relaxed"></textarea>
                            </div>
                        </template>

                        {{-- PHOTO BLOCK --}}
                        <template x-if="block.type === 'photo'">
                            <div class="space-y-3">
                                <input type="file"
                                       accept="image/*"
                                       :id="'block_photo_edit_input_' + bIndex"
                                       class="hidden"
                                       @change="uploadBlockPhoto($event, bIndex)">

                                <div @click="document.getElementById('block_photo_edit_input_' + bIndex).click()"
                                     class="border-2 border-dashed border-[#d4af37]/60 hover:border-[#b8920d] bg-[#fcfaf5] rounded-xl p-6 text-center cursor-pointer transition-all flex flex-col items-center justify-center relative min-h-[90px]">
                                    <template x-if="block.content">
                                        <div class="w-full max-h-48 overflow-hidden rounded-lg relative">
                                            <img :src="block.content.startsWith('storage/') ? '/' + block.content : block.content" class="w-full h-44 object-cover rounded-lg">
                                            <span class="absolute bottom-2 right-2 bg-stone-900/80 text-white text-[9px] font-bold px-2 py-1 rounded">Change Photo</span>
                                        </div>
                                    </template>
                                    <template x-if="!block.content">
                                        <div>
                                            <svg class="w-6 h-6 text-[#d4af37] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-xs font-black text-[#a88210] uppercase tracking-wider">CLICK TO CHOOSE A PHOTO</span>
                                        </div>
                                    </template>
                                </div>

                                <input type="text"
                                       x-model="block.caption"
                                       placeholder="Caption (short)"
                                       class="w-full bg-[#fcfaf5] border border-[#e2d5bd] rounded-xl px-4 py-2.5 text-xs font-medium text-stone-800 focus:bg-white focus:ring-1 focus:ring-[#d4af37] outline-none">
                            </div>
                        </template>

                    </div>
                </template>
            </div>

            {{-- Buttons to Add Blocks --}}
            <div class="pt-2 flex items-center gap-3 flex-wrap">
                <button type="button"
                        @click="addBlock('heading')"
                        class="px-5 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-xs uppercase tracking-wider rounded-full transition-all flex items-center gap-1 cursor-pointer">
                    + Heading
                </button>

                <button type="button"
                        @click="addBlock('paragraph')"
                        class="px-5 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-xs uppercase tracking-wider rounded-full transition-all flex items-center gap-1 cursor-pointer">
                    + Paragraph
                </button>

                <button type="button"
                        @click="addBlock('bullet_list')"
                        class="px-5 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-xs uppercase tracking-wider rounded-full transition-all flex items-center gap-1 cursor-pointer">
                    + Bullet List
                </button>

                <button type="button"
                        @click="addBlock('photo')"
                        class="px-5 py-2 border-2 border-[#d4af37] hover:bg-[#d4af37] hover:text-white text-[#8a6a0b] font-bold text-xs uppercase tracking-wider rounded-full transition-all flex items-center gap-1 cursor-pointer">
                    + Photo
                </button>
            </div>
        </div>

        {{-- CARD 4: PUBLISH & ACTIONS --}}
        <div class="bg-white rounded-2xl border-2 border-[#e6c278]/60 p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }} class="w-4 h-4 text-[#d4af37] rounded border-stone-300 focus:ring-[#d4af37]">
                <span class="text-xs font-black text-stone-800 uppercase tracking-wider">PUBLISH IMMEDIATELY</span>
            </label>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-8 py-3.5 bg-[#d4af37] hover:bg-[#b8920d] text-stone-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-md transition-all">
                    Save Story Changes
                </button>

                <a href="{{ route('admin.blog.index') }}" class="px-6 py-3.5 text-xs font-bold text-stone-500 hover:text-stone-800 uppercase tracking-wider">
                    Cancel
                </a>
            </div>
        </div>

    </form>
</div>

<script>
function blogEditStoryForm() {
    const existingHtml = @json(old('content', $blog->content));
    const coverUrl = @json($blog->featured_image_url);

    return {
        title: @json(old('title', $blog->title)),
        slug: @json(old('slug', $blog->slug)),
        excerpt: @json(old('excerpt', $blog->excerpt ?? '')),
        coverPreview: coverUrl || null,
        blocks: [],
        init() {
            this.blocks = this.parseHtmlToBlocks(existingHtml);
        },
        parseHtmlToBlocks(html) {
            if (!html || !html.trim()) return [
                { id: 1, type: 'heading', content: 'Introduction', caption: '' },
                { id: 2, type: 'paragraph', content: '', caption: '' }
            ];

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const parsed = [];
            let id = 1;

            Array.from(tempDiv.children).forEach(node => {
                const tag = node.tagName ? node.tagName.toLowerCase() : '';
                if (tag === 'h1' || tag === 'h2' || tag === 'h3' || tag === 'h4') {
                    parsed.push({ id: id++, type: 'heading', content: node.textContent.trim(), caption: '' });
                } else if (tag === 'p') {
                    const img = node.querySelector('img');
                    if (img) {
                        parsed.push({ id: id++, type: 'photo', content: img.getAttribute('src') || '', caption: '' });
                    } else if (node.textContent.trim()) {
                        parsed.push({ id: id++, type: 'paragraph', content: node.textContent.trim().replace(/<br\s*\/?>/gi, '\n'), caption: '' });
                    }
                } else if (tag === 'ul' || tag === 'ol') {
                    const items = Array.from(node.querySelectorAll('li')).map(li => li.textContent.trim()).filter(i => i);
                    if (items.length > 0) {
                        parsed.push({ id: id++, type: 'bullet_list', content: items.join('\n'), caption: '' });
                    }
                } else if (tag === 'figure') {
                    const img = node.querySelector('img');
                    const figcaption = node.querySelector('figcaption');
                    if (img) {
                        parsed.push({
                            id: id++,
                            type: 'photo',
                            content: img.getAttribute('src') || '',
                            caption: figcaption ? figcaption.textContent.trim() : ''
                        });
                    }
                } else if (tag === 'img') {
                    parsed.push({ id: id++, type: 'photo', content: node.getAttribute('src') || '', caption: '' });
                }
            });

            return parsed.length > 0 ? parsed : [
                { id: 1, type: 'heading', content: 'Story Content', caption: '' },
                { id: 2, type: 'paragraph', content: html.replace(/<[^>]*>?/gm, '').trim(), caption: '' }
            ];
        },
        previewCover(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => this.coverPreview = e.target.result;
                reader.readAsDataURL(file);
            }
        },
        addBlock(type) {
            let defaultContent = '';
            if (type === 'heading') defaultContent = 'Heading Title';
            this.blocks.push({
                id: Date.now() + Math.random(),
                type: type,
                content: defaultContent,
                caption: ''
            });
        },
        deleteBlock(index) {
            if (this.blocks.length > 1) {
                this.blocks.splice(index, 1);
            }
        },
        moveBlock(index, direction) {
            const target = index + direction;
            if (target >= 0 && target < this.blocks.length) {
                const temp = this.blocks[index];
                this.blocks[index] = this.blocks[target];
                this.blocks[target] = temp;
            }
        },
        uploadBlockPhoto(event, bIndex) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.proposals.upload-day-image') }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.url) {
                    this.blocks[bIndex].content = data.url;
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
        escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        },
        generateHtmlContent() {
            let html = '';
            this.blocks.forEach(block => {
                if (block.type === 'heading' && block.content && block.content.trim()) {
                    html += `<h2>${this.escapeHtml(block.content.trim())}</h2>\n`;
                } else if (block.type === 'paragraph' && block.content && block.content.trim()) {
                    const paragraphs = block.content.split(/\n\s*\n/);
                    paragraphs.forEach(p => {
                        if (p.trim()) {
                            html += `<p>${this.escapeHtml(p.trim()).replace(/\n/g, '<br>')}</p>\n`;
                        }
                    });
                } else if (block.type === 'bullet_list' && block.content && block.content.trim()) {
                    const lines = block.content.split('\n').map(l => l.trim()).filter(l => l);
                    if (lines.length > 0) {
                        html += `<ul>\n`;
                        lines.forEach(line => {
                            html += `  <li>${this.escapeHtml(line.replace(/^[•\-\*]\s*/, ''))}</li>\n`;
                        });
                        html += `</ul>\n`;
                    }
                } else if (block.type === 'photo' && block.content) {
                    html += `<figure class="image">\n`;
                    html += `  <img src="${block.content}" alt="${this.escapeHtml(block.caption || '')}">\n`;
                    if (block.caption && block.caption.trim()) {
                        html += `  <figcaption>${this.escapeHtml(block.caption.trim())}</figcaption>\n`;
                    }
                    html += `</figure>\n`;
                }
            });
            return html;
        }
    }
}
</script>
@endsection
