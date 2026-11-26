<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * One cached map instead of one cache lookup plus one SQL query per key.
     * Defaults stay at read time so a newly added setting is never hidden by
     * a stale cached default.
     *
     * @return array<string, mixed>
     */
    public static function allCached(): array
    {
        try {
            return Cache::remember('all_settings', 3600, fn () => self::query()->pluck('value', 'key')->all());
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function get($key, $default = null)
    {
        return self::allCached()[$key] ?? $default;
    }

    /**
     * The single public contact address used across the site and all outgoing
     * notifications. Older production data still held info@twinasafaris.com,
     * so treat that stale value as unset until the correcting migration runs.
     */
    public static function publicEmail(): string
    {
        $email = strtolower(trim((string) (self::get('site_email', 'twinasafaris@gmail.com') ?? '')));

        if ($email === '' || $email === 'info@twinasafaris.com') {
            return 'twinasafaris@gmail.com';
        }

        return $email;
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_'.$key);
        Cache::forget('all_settings');
    }

    public static function getEmbedUrl($key, $default = null)
    {
        $url = self::get($key, $default);
        if (empty($url)) {
            return null;
        }

        // Extract YouTube video ID
        $youtubePattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        if (preg_match($youtubePattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        // Extract Vimeo video ID
        $vimeoPattern = '/vimeo\.com\/(?:video\/)?([0-9]+)/';
        if (preg_match($vimeoPattern, $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return $url;
    }
}
