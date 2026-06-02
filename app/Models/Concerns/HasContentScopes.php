<?php

namespace App\Models\Concerns;

/**
 * Shared query scopes and route binding for slug-addressable, sortable,
 * toggleable content models (Capability, Service, Solution, Industry, Page, ...).
 */
trait HasContentScopes
{
    use HasOrderableScopes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
