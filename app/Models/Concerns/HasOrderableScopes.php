<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared query scopes for sortable, toggleable models that are NOT
 * addressed by slug (e.g. TechStack, Stat — managed in admin only).
 */
trait HasOrderableScopes
{
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order');
    }
}
