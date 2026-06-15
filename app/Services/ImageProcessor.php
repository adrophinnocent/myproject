<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageProcessor
{
    protected $manager;

    public function __construct()
    {
        $this->manager = ImageManager::gd();
    }

    public function process(UploadedFile $file, string $directory = 'ai-images'): array
    {
        // Generate unique filenames
        $originalFilename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $optimizedFilename = uniqid() . '_' . time() . '.webp';

        // Store original image
        $originalPath = $file->storeAs($directory . '/original', $originalFilename, 'public');

        // Process and store optimized WebP
        $image = $this->manager->read($file->getPathname());

        // Resize to max 1200px width while maintaining aspect ratio
        $image->scale(width: 1200);

        // Save as WebP with 80% quality
        $optimizedContent = $image->toWebp(quality: 80);
        Storage::disk('public')->put($directory . '/optimized/' . $optimizedFilename, $optimizedContent);

        return [
            'original' => $originalPath,
            'optimized' => $directory . '/optimized/' . $optimizedFilename,
        ];
    }
}
