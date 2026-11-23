<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'search_question_id',
        'title',
        'content',
        'meta_description',
        'suggested_links',
        'related_questions',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'suggested_links' => 'array',
            'related_questions' => 'array',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SearchQuestion::class, 'search_question_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
