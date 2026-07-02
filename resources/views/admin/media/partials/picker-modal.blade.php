{{-- reusable-media-picker-modal --}}
<div x-data="mediaPicker()"
     @open-media-picker.window="openPicker($event.detail)"
     x-show="isOpen"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-10 bg-black/60 backdrop-blur-md">

    <div class="bg-white rounded-[2.5rem] w-full max-w-6xl h-full max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fade-up">

        {{-- Modal Header --}}
        <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Select Media</h3>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">Pick an optimized asset for your content</p>
            </div>
            <button @click="closePicker" class="w-12 h-12 neo-btn flex items-center justify-center text-gray-400 hover:text-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Toolbar --}}
        <div class="px-8 py-4 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3">
                <select x-model="filterType" @change="fetchMedia" class="bg-gray-100 border-none rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 focus:ring-amber-500/20">
                    <option value="">All Types</option>
                    <option value="image">Images</option>
                    <option value="video">Videos</option>
                </select>
                <div class="relative w-64">
                    <input type="text" x-model="search" @input.debounce.500ms="fetchMedia" placeholder="Search..." class="w-full bg-gray-100 border-none rounded-xl pl-10 py-2.5 text-xs font-bold focus:ring-2 focus:ring-amber-500/20">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button @click="$refs.pickerUploadInput.click()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Upload New
                </button>
                <input type="file" x-ref="pickerUploadInput" class="hidden" @change="handleUpload">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-4 py-2 rounded-full hidden md:block">
                    Click an item to select it
                </p>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 overflow-y-auto p-8 no-scrollbar bg-white relative">
            {{-- Upload Overlay --}}
            <div x-show="isUploading" x-cloak class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center">
                <svg class="animate-spin h-10 w-10 text-amber-500 mb-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Optimizing & Saving...</p>
            </div>
            <div x-show="isLoading" class="flex flex-col items-center justify-center py-20">
                <svg class="animate-spin h-10 w-10 text-amber-500 mb-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Loading Library...</p>
            </div>

            <div x-show="!isLoading" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6" id="picker-media-grid">
                {{-- Dynamic Content --}}
                <template x-for="item in mediaItems" :key="item.id">
                    <div @click="selectItem(item)" class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:border-amber-500 cursor-pointer transition-all duration-300 relative aspect-square">
                        <template x-if="item.type === 'image'">
                            <img :src="item.url" class="w-full h-full object-cover">
                        </template>
                        <template x-if="item.type !== 'image'">
                            <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                                <span class="text-xs font-black uppercase tracking-tighter" x-text="item.type"></span>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-amber-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                </template>
            </div>

            <div x-show="!isLoading && mediaItems.length === 0" class="text-center py-20">
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No matching assets found</p>
            </div>
        </div>

        {{-- Pagination Placeholder --}}
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-center">
            <button x-show="hasMore" @click="loadMore" class="text-[10px] font-black text-amber-600 uppercase tracking-widest hover:underline">Load More</button>
        </div>
    </div>
</div>

<script>
function mediaPicker() {
    return {
        isOpen: false,
        isLoading: false,
        isUploading: false,
        mediaItems: [],
        filterType: '',
        search: '',
        targetId: '',
        previewId: '',
        page: 1,
        hasMore: false,

        openPicker(config) {
            console.log('Opening picker with config:', config);
            this.targetId = config.targetId;
            this.previewId = config.previewId;
            this.isOpen = true;
            this.fetchMedia();
        },

        async handleUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Optional: Ask for alt text for SEO
            const alt = prompt('Enter Alt Text for SEO (e.g. Serengeti Safari):', file.name.split('.')[0]);
            if (alt === null) return; // Cancelled

            this.isUploading = true;
            const formData = new FormData();
            formData.append('file', file);
            formData.append('alt', alt);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('admin.media.upload') }}', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    await this.fetchMedia(); // Refresh list
                    this.selectItem(result.media); // Auto select uploaded item
                } else {
                    alert(result.message || 'Upload failed');
                }
            } catch (err) {
                console.error(err);
                alert('Connection error during upload');
            } finally {
                this.isUploading = false;
                e.target.value = ''; // Reset input
            }
        },

        closePicker() {
            this.isOpen = false;
        },

        async fetchMedia(append = false) {
            if (!append) {
                this.isLoading = true;
                this.page = 1;
            }

            const url = new URL('{{ route('admin.media.index') }}');
            if (this.filterType) url.searchParams.append('type', this.filterType);
            if (this.search) url.searchParams.append('search', this.search);
            url.searchParams.append('page', this.page);

            try {
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await response.json();

                if (append) {
                    this.mediaItems = [...this.mediaItems, ...data.data];
                } else {
                    this.mediaItems = data.data;
                }

                this.hasMore = data.next_page_url !== null;
            } catch (e) {
                console.error('Picker error', e);
            } finally {
                this.isLoading = false;
            }
        },

        loadMore() {
            if (this.hasMore && !this.isLoading) {
                this.page++;
                this.fetchMedia(true);
            }
        },

        selectItem(item) {
            console.log('Selecting item:', item);
            // Set the value of the target input (either path or ID)
            const targetEl = document.getElementById(this.targetId);
            if (targetEl) {
                targetEl.value = item.path;
                // Important for Alpine.js or other listeners
                targetEl.dispatchEvent(new Event('input', { bubbles: true }));
                targetEl.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                console.error('Target element not found:', this.targetId);
            }

            // Update preview if exists
            const previewEl = document.getElementById(this.previewId);
            if (previewEl) {
                if (item.type === 'image') {
                    previewEl.src = item.url;
                    previewEl.classList.remove('hidden');
                }
            } else {
                console.warn('Preview element not found:', this.previewId);
            }

            this.closePicker();
        }
    }
}
</script>
