<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AssetHelper
{
    // Clean and robust asset helper for Twinasafaris
    /**
     * Ensure we always return a string for Blade output.
     */
    public static function asString($value, $default = '')
    {
        if (is_array($value)) {
            return implode(', ', $value);
        }
        return (string) ($value ?? $default);
    }

    /**
     * Get the URL for a banner, trying different extensions.
     */
    public static function getBannerUrl($name, $default = null)
    {
        try {
            // Check Admin Settings First
            $settingValue = \App\Models\Setting::get($name);
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }

            $extensions = ['webp', 'jpg', 'jpeg', 'png', 'JPG', 'PNG', 'JPEG', 'WEBP'];

            // 1. Check in kilimanjaro folder
            foreach ($extensions as $ext) {
                $path = 'images/kilimanjaro/' . $name . '.' . $ext;
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }

            // 2. Check in banners folder
            foreach ($extensions as $ext) {
                $path = 'images/banners/' . $name . '.' . $ext;
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }

            // 3. Check in root images folder
            foreach ($extensions as $ext) {
                $path = 'images/' . $name . '.' . $ext;
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Banner Error: " . $e->getMessage());
        }

        return $default ?: asset('images/banners/hero_fallback.webp');
    }

    /**
     * Get Open Graph Image URL.
     */
    public static function getOgImageUrl($default = null)
    {
        try {
            // 1. Check Admin Setting for specific OG image
            $settingValue = \App\Models\Setting::get('og_image');
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }

            // 2. Check if og-default.jpg exists
            if (file_exists(public_path('images/og-default.jpg'))) {
                return asset('images/og-default.jpg');
            }

            // 3. Fallback to a high-quality safari image if available
            if (file_exists(public_path('images/big-five/lion.webp'))) {
                return asset('images/big-five/lion.webp');
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper OG Image Error: " . $e->getMessage());
        }

        return $default ?: self::getLogoUrl();
    }

    /**
     * Get Logo URL.
     */
    public static function getLogoUrl($type = 'logo')
    {
        try {
            // 1. Check Admin Setting
            $settingValue = \App\Models\Setting::get($type);
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }

            // 2. Check public/uploads (New Simple System)
            $uploadPaths = ["uploads/{$type}.png", "uploads/{$type}.jpg", "uploads/logo/{$type}.png"];
            foreach ($uploadPaths as $path) {
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }

            // 3. Check public/images/logos
            $extensions = ['webp', 'png', 'jpg', 'jpeg', 'svg'];
            foreach ($extensions as $ext) {
                $localPath = "images/logos/{$type}.{$ext}";
                if (file_exists(public_path($localPath))) {
                    return asset($localPath);
                }
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Logo Error: " . $e->getMessage());
        }

        return asset("images/logo.png");
    }

    /**
     * Get Favicon URL.
     */
    public static function getFaviconUrl()
    {
        try {
            // 1. Prioritize Admin Setting
            $settingValue = \App\Models\Setting::get('favicon');
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }

            // 2. Check public/uploads
            if (file_exists(public_path('uploads/favicon.ico'))) {
                return asset('uploads/favicon.ico');
            }

            // 3. Fallback to local files
            $paths = ['favicon.ico', 'images/logos/favicon.ico', 'images/favicon.ico'];
            foreach ($paths as $path) {
                if (file_exists(public_path($path)) && filesize(public_path($path)) > 0) {
                    return asset($path);
                }
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Favicon Error: " . $e->getMessage());
        }

        return asset('favicon.ico');
    }
}
