<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'filename', 'path', 'thumb',
        'type', 'mime_type', 'size', 'url', 'alt',
    ];

    public function getSizeHumanAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }

    public function render($class = '', $id = '')
    {
        return sprintf(
            '<img src="%s" alt="%s" class="%s" id="%s" loading="lazy" decoding="async">',
            $this->url,
            e($this->alt),
            $class,
            $id
        );
    }
}
