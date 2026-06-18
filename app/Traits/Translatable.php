<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Get the translation for a specific field.
     * Use: $model->translate('title')
     */
    public function translate(string $field, ?string $locale = null): mixed
    {
        $locale = $locale ?? App::getLocale();
        $defaultLocale = config('app.locale', 'en');

        // If requested locale is the default, return original attribute
        if ($locale === $defaultLocale) {
            return $this->{$field};
        }

        // Use the translations relationship (polymorphic OR dedicated)
        $translationRelation = $this->translations();
        $relatedModel = $translationRelation->getRelated();

        if ($relatedModel instanceof Translation) {
            // Polymorphic style (like BlogPost)
            $translation = $translationRelation->where('locale', $locale)
                ->where('field', $field)
                ->first();
            return $translation ? $translation->text : $this->{$field};
        } else {
            // Dedicated table style (like Tour)
            $translation = $translationRelation->where('locale', $locale)->first();
            return ($translation && !empty($translation->{$field})) ? $translation->{$field} : $this->{$field};
        }
    }

    /**
     * Relationship to translations.
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }
}
