@extends('admin.layouts.app')

@section('title', 'Media Manager')
@section('page-title', 'Advanced Media Manager')

@section('content')
<div class="space-y-6" x-data="mediaManager()">
    {{-- Top Toolbar --}}
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <button @click="$refs.fileInput.click()" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Upload New
            </button>
            <input type="file" x-ref="fileInput" class="hidden" @change="handleFileUpload">

            <div class="h-8 w-px bg-gray-200 mx-2"></div>

            <select x-model="filterType" @change="refreshList()" class="bg-gray-50 border-none rounded-xl text-xs font-bold uppercase tracking-wider text-gray-500 focus:ring-0">
                <option value="">All Media</option>
                <option value="image">Images</option>
                <option value="video">Videos</option>
                <option value="document">Documents</option>
            </select>
        </div>

        <div class="relative w-full md:w-72">
            <input type="text" x-model="search" @input.debounce.500ms="refreshList()" placeholder="Search media..." class="w-full bg-gray-50 border-none rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/20">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    {{-- Media Grid --}}
    <div id="media-list" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @include('admin.media.partials.list')
    </div>

    {{-- Upload Modal --}}
    <div x-show="showUploadModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-10 shadow-2xl animate-fade-up">
            <h3 class="text-2xl font-black text-gray-900 mb-2">Optimize & Upload</h3>
            <p class="text-sm text-gray-500 mb-8">Set mandatory details to boost SEO & Performance.</p>

            <div class="space-y-6">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-xl overflow-hidden border border-gray-200">
                        <img :src="uploadPreview" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate" x-text="selectedFile?.name"></p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" x-text="formatSize(selectedFile?.size)"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Mandatory Alt Text (SEO)</label>
                    <input type="text" x-model="uploadAlt" placeholder="Describe the image (e.g. Lion in Serengeti)" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-amber-500/20 outline-none font-bold">
                    <p class="text-[10px] text-amber-600 mt-2 font-bold" x-show="!uploadAlt">Alt text is required for images!</p>
                </div>

                <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-2xl text-blue-700">
                    <span class="text-lg">⚡</span>
                    <p class="text-[10px] font-bold leading-tight">Image will be automatically compressed and converted to WebP format for 3x faster loading speed.</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button @click="closeUpload" class="flex-1 py-4 rounded-2xl font-black uppercase text-xs tracking-widest text-gray-400 hover:bg-gray-50">Cancel</button>
                    <button @click="processUpload" :disabled="!uploadAlt || isUploading" class="flex-1 btn-gold py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl disabled:opacity-50">
                        <span x-show="!isUploading">Optimize & Save</span>
                        <span x-show="isUploading" class="flex items-center gap-2 justify-center">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mediaManager() {
    return {
        showUploadModal: false,
        isUploading: false,
        selectedFile: null,
        uploadPreview: '',
        uploadAlt: '',
        search: '',
        filterType: '',

        handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            this.selectedFile = file;
            this.uploadAlt = '';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.uploadPreview = e.target.result;
                    this.showUploadModal = true;
                };
                reader.readAsDataURL(file);
            } else {
                this.uploadPreview = ''; // Handle non-image icons
                this.processUpload(); // Skip modal for non-images if alt not required
            }
        },

        closeUpload() {
            this.showUploadModal = false;
            this.selectedFile = null;
            this.uploadPreview = '';
            this.uploadAlt = '';
        },

        async processUpload() {
            if (this.isUploading) return;
            this.isUploading = true;

            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('alt', this.uploadAlt);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('admin.media.upload') }}', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    const errData = await response.json();
                    throw new Error(errData.message || 'Server error');
                }

                const result = await response.json();
                if (result.success) {
                    this.closeUpload();
                    this.refreshList();
                } else {
                    alert(result.message || 'Upload failed');
                }
            } catch (error) {
                console.error(error);
                alert('Upload Error: ' + error.message);
            } finally {
                this.isUploading = false;
            }
        },

        refreshList() {
            const url = new URL('{{ route('admin.media.index') }}');
            if (this.search) url.searchParams.append('search', this.search);
            if (this.filterType) url.searchParams.append('type', this.filterType);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    document.getElementById('media-list').innerHTML = html;
                });
        },

        formatSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        deleteMedia(id) {
            if (!confirm('Delete this file permanently?')) return;

            fetch(`/admin/media/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => this.refreshList());
        }
    }
}
</script>
@endsection
