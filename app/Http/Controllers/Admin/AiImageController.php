<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiImage;
use App\Services\ImageProcessor;
use App\Services\AIGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AiImageController extends Controller
{
    protected $imageProcessor;
    protected $aiGenerator;

    public function __construct(ImageProcessor $imageProcessor, AIGenerator $aiGenerator)
    {
        $this->imageProcessor = $imageProcessor;
        $this->aiGenerator = $aiGenerator;
    }

    public function index()
    {
        $images = AiImage::latest()->paginate(12);
        return view('admin.ai-images.index', compact('images'));
    }

    public function create()
    {
        $categories = ['destination', 'tour', 'gallery', 'banner'];
        return view('admin.ai-images.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:10240', // 10MB max
            'category' => 'required|in:destination,tour,gallery,banner',
            'related_id' => 'nullable|integer'
        ]);

        // Process the image
        $processedImages = $this->imageProcessor->process($request->file('image'));

        // Generate AI content
        $aiContent = $this->aiGenerator->generateImageContent(
            $processedImages['optimized'],
            $request->category
        );

        // Store data in session for preview
        Session::flash('uploaded_image', [
            'original_path' => $processedImages['original'],
            'optimized_path' => $processedImages['optimized'],
            'category' => $request->category,
            'related_id' => $request->related_id,
            'ai_content' => $aiContent
        ]);

        return redirect()->route('admin.ai-images.preview');
    }

    public function preview()
    {
        $uploaded = Session::get('uploaded_image');

        if (!$uploaded) {
            return redirect()->route('admin.ai-images.create');
        }

        return view('admin.ai-images.preview', $uploaded);
    }

    public function confirm(Request $request)
    {
        $uploaded = Session::get('uploaded_image');

        if (!$uploaded) {
            return redirect()->route('admin.ai-images.create');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'tags' => 'nullable|string'
        ]);

        $tags = array_map('trim', explode(',', $validated['tags'] ?? ''));
        $tags = array_filter($tags);

        AiImage::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'alt_text' => $validated['alt_text'],
            'tags' => $tags,
            'image_path' => $uploaded['optimized_path'],
            'original_image_path' => $uploaded['original_path'],
            'category' => $uploaded['category'],
            'related_id' => $uploaded['related_id'],
        ]);

        Session::forget('uploaded_image');

        return redirect()->route('admin.ai-images.index')
            ->with('success', 'Image uploaded and saved successfully!');
    }

    public function edit(AiImage $aiImage)
    {
        return view('admin.ai-images.edit', compact('aiImage'));
    }

    public function update(Request $request, AiImage $aiImage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'tags' => 'nullable|string'
        ]);

        $tags = array_map('trim', explode(',', $validated['tags'] ?? ''));
        $tags = array_filter($tags);

        $aiImage->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'alt_text' => $validated['alt_text'],
            'tags' => $tags,
        ]);

        return redirect()->route('admin.ai-images.index')
            ->with('success', 'Image updated successfully!');
    }

    public function destroy(AiImage $aiImage)
    {
        // Delete files from storage
        if ($aiImage->image_path) {
            Storage::disk('public')->delete($aiImage->image_path);
        }
        if ($aiImage->original_image_path) {
            Storage::disk('public')->delete($aiImage->original_image_path);
        }

        $aiImage->delete();

        return redirect()->route('admin.ai-images.index')
            ->with('success', 'Image deleted successfully!');
    }
}
