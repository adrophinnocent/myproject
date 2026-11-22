<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiVisibilityCheck extends Model
{
    protected $fillable = [
        'query_text',
        'ai_platform',
        'is_brand_mentioned',
        'is_source_cited',
        'competitors_mentioned',
        'source_link',
        'check_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_brand_mentioned' => 'boolean',
            'is_source_cited' => 'boolean',
            'competitors_mentioned' => 'array',
            'check_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
