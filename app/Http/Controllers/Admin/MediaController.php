<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('alt', 'like', '%'.$request->search.'%');
            });
        }

        $media = $query->paginate(24);

        if ($request->wantsJson()) {
            return response()->json($media);
        }

        if ($request->ajax()) {
            return view('admin.media.partials.list', compact('media'));
        }

        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['success' => false, 'message' => 'No file provided.'], 422);
            }

            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg,mp4,pdf|max:20480',
                'alt' => 'nullable|string|max:255',
            ]);

            $file = $request->file('file');
            $mime = $file->getMimeType();
            $type = Str::startsWith($mime, 'image/') ? 'image'
                   : (Str::startsWith($mime, 'video/') ? 'video' : 'document');

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $folder = 'media/'.date('Y/m');

            if ($type === 'image' && $extension !== 'svg') {
                $filename = Str::slug($name).'-'.uniqid().'.webp';
                $path = $folder.'/'.$filename;

                // Optimization using native PHP functions
                $source = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($source) {
                    $width = imagesx($source);
                    $height = imagesy($source);

                    // Optional: Scale down if very large
                    if ($width > 2000) {
                        $newWidth = 2000;
                        $newHeight = ($height / $width) * $newWidth;
                        $scaled = imagecreatetruecolor($newWidth, $newHeight);
                        imagealphablending($scaled, false);
                        imagesavealpha($scaled, true);
                        imagecopyresampled($scaled, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagedestroy($source);
                        $source = $scaled;
                    }

                    ob_start();
                    imagewebp($source, null, 80); // 80% quality
                    $content = ob_get_clean();
                    Storage::disk('public')->put($path, $content);
                    imagedestroy($source);
                    $mime = 'image/webp';
                } else {
                    // Fallback to regular upload if image processing fails
                    $filename = Str::slug($name).'-'.uniqid().'.'.$extension;
                    $path = $file->storeAs($folder, $filename, 'public');
                }
            } else {
                $filename = Str::slug($name).'-'.uniqid().'.'.$extension;
                $path = $file->storeAs($folder, $filename, 'public');
            }

            if (!$path) {
                throw new \Exception("Failed to store file on disk.");
            }

            $media = Media::create([
                'name' => $name,
                'filename' => $filename,
                'path' => $path,
                'type' => $type,
                'mime_type' => $mime,
                'size' => Storage::disk('public')->size($path),
                'url' => Storage::disk('public')->url($path),
                'alt' => $request->alt ?? $name,
            ]);

            return response()->json([
                'success' => true,
                'media' => $media,
                'message' => 'File uploaded and optimized successfully.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Media Upload Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);
        if ($media->thumb) {
            Storage::disk('public')->delete($media->thumb);
        }
        $media->delete();

        return response()->json(['success' => true, 'message' => 'File deleted.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Media::whereIn('id', $ids)->get()->each(function ($m) {
            Storage::disk('public')->delete($m->path);
            $m->delete();
        });

        return response()->json(['success' => true, 'message' => 'Files deleted.']);
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alt' => 'required|string|max:255'
        ]);

        $media->update([
            'name' => $request->name,
            'alt' => $request->alt
        ]);

        return response()->json(['success' => true, 'media' => $media]);
    }
}
