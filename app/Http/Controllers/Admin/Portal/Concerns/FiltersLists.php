<?php

namespace App\Http\Controllers\Admin\Portal\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Shared filter / search / sort behaviour for portal list screens, so every
 * index answers the same query strings in the same way (§23).
 */
trait FiltersLists
{
    /**
     * Apply an exact-match filter when the query string carries a value.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     */
    protected function filterEquals(Builder $query, Request $request, string $key, ?string $column = null): void
    {
        $value = $request->query($key);

        if ($value === null || $value === '') {
            return;
        }

        $query->where($column ?? $key, $value);
    }

    /**
     * Apply a LIKE search across several columns from a single `q` parameter.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     * @param  list<string>  $columns
     */
    protected function filterSearch(Builder $query, Request $request, array $columns, string $key = 'q'): void
    {
        $term = trim((string) $request->query($key));

        if ($term === '') {
            return;
        }

        $query->where(function (Builder $builder) use ($columns, $term): void {
            foreach ($columns as $column) {
                $builder->orWhere($column, 'like', '%'.$term.'%');
            }
        });
    }

    /**
     * Apply an inclusive date range from `{key}_from` / `{key}_to`.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     */
    protected function filterDateRange(Builder $query, Request $request, string $column, string $key): void
    {
        if ($from = $request->query($key.'_from')) {
            $query->where($column, '>=', Carbon::parse($from)->startOfDay());
        }

        if ($to = $request->query($key.'_to')) {
            $query->where($column, '<=', Carbon::parse($to)->endOfDay());
        }
    }

    /**
     * Sort by a whitelisted column, falling back to a sane default. The
     * whitelist is what keeps `?sort=` from becoming an injection point.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     * @param  list<string>  $sortable
     */
    protected function applySort(Builder $query, Request $request, array $sortable, string $default, string $defaultDirection = 'desc'): void
    {
        $column = (string) $request->query('sort', $default);
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        if (! in_array($column, $sortable, true)) {
            $column = $default;
            $direction = $defaultDirection;
        }

        $query->orderBy($column, $direction);
    }

    /**
     * Query-string values to preserve across pagination links.
     *
     * @return array<string, mixed>
     */
    protected function filterState(Request $request): array
    {
        return array_filter(
            $request->query(),
            fn (mixed $value): bool => $value !== null && $value !== '',
        );
    }
}
