<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchQuestion extends Model
{
    use HasSlug, SoftDeletes;

    protected $slugSource = 'question';

    protected $fillable = [
        'category_id',
        'tour_id',
        'question',
        'slug',
        'primary_keyword',
        'secondary_keywords',
        'search_intent',
        'target_country',
        'suggested_content_type',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'secondary_keywords' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SearchQuestionCategory::class, 'category_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function answer(): HasOne
    {
        return $this->hasOne(SearchAnswer::class, 'search_question_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SearchContentAssignment::class, 'search_question_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
