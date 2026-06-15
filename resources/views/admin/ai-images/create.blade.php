@extends('admin.layouts.app')

@section('title', 'Upload New Image')
@section('page-title', 'Upload New Image')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <form method="POST" action="{{ route('admin.ai-images.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Image</label>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-[#D4AF37] transition-colors">
                <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png" class="hidden" onchange="previewImage(event)">
                <label for="image" class="cursor-pointer">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500">Supports: JPG, JPEG, PNG (Max 10MB)</p>
                </label>
                <div id="preview-container" class="mt-4 hidden">
                    <img id="preview" class="max-w-xs mx-auto rounded-lg shadow-sm">
                </div>
            </div>
            @error('image')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select name="category" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            @error('category')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Related ID (Optional)</label>
            <input type="number" name="related_id" value="{{ old('related_id') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="text-xs text-gray-500 mt-1">Link this image to a specific tour, destination, etc.</p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.ai-images.index') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">Cancel</a>
            <button type="submit" class="flex-1 bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-3 rounded-lg transition-colors">Upload & Process</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const container = document.getElementById('preview-container');

    if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        }

        reader.readAsDataURL(event.target.files[0]);
    } else {
        container.classList.add('hidden');
    }
}
</script>
@endsection
