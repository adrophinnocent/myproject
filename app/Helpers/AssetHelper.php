<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AssetHelper
{
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
            $extensions = ['webp', 'jpg', 'jpeg', 'png'];
            $basePath = 'images/banners/';

            foreach ($extensions as $ext) {
                $path = $basePath . $name . '.' . $ext;
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Banner Error: " . $e->getMessage());
        }

        return $default ?: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=2000&q=80';
    }

    /**
     * Get Logo URL, checking local storage first then database settings.
     */
    public static function getLogoUrl($type = 'logo')
    {
        try {
            $extensions = ['webp', 'png', 'jpg', 'jpeg'];
            foreach ($extensions as $ext) {
                $localPath = "images/logos/{$type}.{$ext}";
                if (File::exists(public_path($localPath))) {
                    return asset($localPath);
                }
            }

            $settingValue = \App\Models\Setting::get($type);
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Logo Error: " . $type . " - " . $e->getMessage());
        }

        return asset("images/logo.png");
    }

    /**
     * Get Favicon URL.
     */
    public static function getFaviconUrl()
    {
        try {
            if (file_exists(public_path('images/logos/favicon.ico'))) {
                return asset('images/logos/favicon.ico');
            }

            $settingValue = \App\Models\Setting::get('favicon');
            if ($settingValue && is_string($settingValue)) {
                return asset('storage/' . $settingValue);
            }
        } catch (\Throwable $e) {
            Log::error("AssetHelper Favicon Error: " . $e->getMessage());
        }

        return asset('favicon.ico');
    }
}
