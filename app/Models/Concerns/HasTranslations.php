<?php

namespace App\Models\Concerns;

use App\Support\ContentTranslations;

trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeFillable(['translations']);
        $this->mergeCasts(['translations' => 'array']);
    }

    public function getAttribute($key)
    {
        if (app()->getLocale() === 'en'
            && in_array($key, ContentTranslations::FIELDS[class_basename($this)] ?? [], true)) {
            $translated = parent::getAttribute('translations')['en'][$key] ?? null;
            if (filled($translated)) {
                return $translated;
            }

            $original = parent::getAttribute($key);

            return is_string($original) ? __($original) : $original;
        }

        return parent::getAttribute($key);
    }
}
