<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getEmbedUrl($key, $default = null)
    {
        $url = self::get($key, $default);
        if (empty($url)) return null;

        // Extract YouTube video ID
        $youtubePattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        if (preg_match($youtubePattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Extract Vimeo video ID
        $vimeoPattern = '/vimeo\.com\/(?:video\/)?([0-9]+)/';
        if (preg_match($vimeoPattern, $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return $url;
    }
}
