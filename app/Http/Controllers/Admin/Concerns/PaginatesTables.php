<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\AdminPagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Server-side pagination and search for every admin listing (spec §28.4):
 * 10/20/50/100 rows per page (default 20), and a `q` search term that stays in
 * the page links together with any other filter or sort parameters.
 *
 * Pair with the <x-admin.search-form> and <x-admin.pagination> components.
 */
trait PaginatesTables
{
    /**
     * Search the given columns for `q`, then paginate keeping the query string.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @param  list<string>  $searchable
     * @return LengthAwarePaginator<int, TModel>
     */
    protected function paginateTable(Request $request, Builder $query, array $searchable = []): LengthAwarePaginator
    {
        $search = $this->searchTerm($request);

        if ($search !== '' && $searchable !== []) {
            $query->where(function (Builder $query) use ($searchable, $search): void {
                foreach ($searchable as $column) {
                    $query->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }

        return $query->paginate($this->perPage($request))->withQueryString();
    }

    /**
     * The requested page size, constrained to the allowed options.
     */
    protected function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', (string) AdminPagination::DEFAULT_PAGE_SIZE);

        return in_array($perPage, AdminPagination::PAGE_SIZES, true) ? $perPage : AdminPagination::DEFAULT_PAGE_SIZE;
    }

    /**
     * The trimmed search term, capped to a sane length.
     */
    protected function searchTerm(Request $request): string
    {
        return Str::limit(trim((string) $request->query('q', '')), 100, '');
    }
}
