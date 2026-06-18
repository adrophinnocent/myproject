<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 * @method static \Illuminate\Database\Eloquent\Builder creating(callable $callback)
 * @method static \Illuminate\Database\Eloquent\Builder updating(callable $callback)
 * @method static \Illuminate\Database\Eloquent\Builder where(string $column, mixed $operator = null, mixed $value = null)
 */
trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });

        static::updating(function (Model $model) {
            $model->generateSlug();
        });
    }

    /**
     * Generate a unique slug for the model.
     */
    protected function generateSlug(): void
    {
        $sourceField = $this->getSlugSourceField();
        $slugField = $this->getSlugField();

        // If slug is provided manually and is not empty, use it but ensure uniqueness
        if ($this->isDirty($slugField) && !empty(trim($this->$slugField))) {
            $this->$slugField = $this->makeSlugUnique(Str::slug($this->$slugField));
            return;
        }

        // If we have a source field value, generate a slug from it
        if (!empty($this->$sourceField)) {
            // Generate a new slug if source changed or slug is empty
            if ($this->isDirty($sourceField) || empty($this->$slugField) || !$this->exists) {
                $slug = Str::slug($this->$sourceField);
                $this->$slugField = $this->makeSlugUnique($slug);
            }
        }
    }

    /**
     * Make sure the slug is unique in the database.
     */
    protected function makeSlugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $count = 1;
        $slugField = $this->getSlugField();

        // If slug is empty, create a generic one
        if (empty($originalSlug)) {
            $originalSlug = 'item';
        }

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Check if the slug already exists.
     */
    protected function slugExists(string $slug): bool
    {
        $slugField = $this->getSlugField();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = static::where($slugField, $slug);

        if ($this->getKey()) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        return $query->exists();
    }

    /**
     * Get the name of the field to generate the slug from.
     */
    protected function getSlugSourceField(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'name';
    }

    /**
     * Get the name of the slug field.
     */
    protected function getSlugField(): string
    {
        return property_exists($this, 'slugField') ? $this->slugField : 'slug';
    }
}
