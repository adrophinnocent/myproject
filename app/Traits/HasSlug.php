<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
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

        // If slug is provided manually, use it but ensure uniqueness
        if ($this->isDirty($slugField) && !empty($this->$slugField)) {
            $this->$slugField = $this->makeSlugUnique(Str::slug($this->$slugField));
            return;
        }

        // If source field is dirty or slug is empty, generate new slug
        if ($this->isDirty($sourceField) || empty($this->$slugField)) {
            $slug = Str::slug($this->$sourceField);
            $this->$slugField = $this->makeSlugUnique($slug);
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
