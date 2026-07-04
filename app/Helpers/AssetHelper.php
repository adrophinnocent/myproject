<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class AssetHelper
{
    /**
     * Get the URL for a banner, trying different extensions.
     */
    public static function getBannerUrl($name, $default = null)
    {
        $extensions = ['webp', 'jpg', 'jpeg', 'png'];
        $basePath = 'images/banners/';

        foreach ($extensions as $ext) {
            $path = $basePath . $name . '.' . $ext;
            if (File::exists(public_path($path))) {
                return asset($path);
            }
        }

    /**
     * Get Logo URL, checking local storage first then database settings.
     */
    public static function getLogoUrl($type = 'logo')
    {
        $localPath = "images/logos/{$type}.webp";
        if (File::exists(public_path($localPath))) {
            return asset($localPath);
        }

        $settingValue = \App\Models\Setting::get($type);
        if ($settingValue) {
            return asset('storage/' . $settingValue);
        }

        return asset("images/{$type}.png");
    }

    /**
     * Get Favicon URL.
     */
    public static function getFaviconUrl()
    {
        if (File::exists(public_path('images/logos/favicon.ico'))) {
            return asset('images/logos/favicon.ico');
        }

        $settingValue = \App\Models\Setting::get('favicon');
        if ($settingValue) {
            return asset('storage/' . $settingValue);
        }

        return asset('favicon.ico');
    }
}
