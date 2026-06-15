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
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $media = $query->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,svg,mp4,pdf|max:20480',
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $mime = $file->getMimeType();
            $type = Str::startsWith($mime, 'image/') ? 'image'
                       : (Str::startsWith($mime, 'video/') ? 'video' : 'document');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $folder = 'media/'.date('Y/m');

            if ($type === 'image' && !in_array($ext, ['webp', 'svg', 'gif'])) {
                // Convert to WebP
                $filename = Str::slug($name).'-'.uniqid().'.webp';
                $path = $folder . '/' . $filename;

                $image = match($ext) {
                    'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
                    'png' => imagecreatefrompng($file->getRealPath()),
                    default => null,
                };

                if ($image) {
                    if (!Storage::disk('public')->exists($folder)) {
                        Storage::disk('public')->makeDirectory($folder);
                    }

                    $fullPath = Storage::disk('public')->path($path);
                    imagewebp($image, $fullPath, 80);
                    imagedestroy($image);

                    $mime = 'image/webp';
                    $size = filesize($fullPath);
                } else {
                    $filename = Str::slug($name).'-'.uniqid().'.'.$ext;
                    $path = $file->storeAs($folder, $filename, 'public');
                    $size = $file->getSize();
                }
            } else {
                $filename = Str::slug($name).'-'.uniqid().'.'.$ext;
                $path = $file->storeAs($folder, $filename, 'public');
                $size = $file->getSize();
            }

            $media = Media::create([
                'name' => $name,
                'filename' => $filename,
                'path' => $path,
                'thumb' => null,
                'type' => $type,
                'mime_type' => $mime,
                'size' => $size,
                'url' => Storage::disk('public')->url($path),
            ]);

            $uploaded[] = $media;
        }

        return response()->json([
            'success' => true,
            'files' => $uploaded,
            'message' => count($uploaded).' file(s) uploaded successfully.',
        ]);
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
            if ($m->thumb) {
                Storage::disk('public')->delete($m->thumb);
            }
            $m->delete();
        });

        return response()->json(['success' => true, 'message' => count($ids).' file(s) deleted.']);
    }

    public function update(Request $request, Media $media)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $media->update(['name' => $request->name, 'alt' => $request->alt]);

        return response()->json(['success' => true, 'media' => $media]);
    }
}
