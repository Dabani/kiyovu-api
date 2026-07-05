<?php

namespace App\Traits;

trait IsLookup
{
    public function initializeIsLookup(): void
    {
        $this->fillable([
            'code', 'label_en', 'label_fr', 'label_rw','is_active', 'sort_order',
        ]);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label_en');
    }

    /** Locale-aware label, falls back to English. */
    public function label(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $field = "label_{$locale}";

        return $this->{$field} ?? $this->label_en;
    }
}
