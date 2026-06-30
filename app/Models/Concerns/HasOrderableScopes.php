<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared query scopes for sortable, toggleable models that are NOT
 * addressed by slug (e.g. TechStack, Stat — managed in admin only).
 *
 * Every model using this trait (directly or via HasContentScopes) feeds a
 * long-lived public-site cache, so it also flushes those caches on save/delete.
 */
trait HasOrderableScopes
{
    use FlushesSiteCache;

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order');
    }
}
