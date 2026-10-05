@props(['paginator'])

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $window = 2;
    $from = max(1, $current - $window);
    $to = min($last, $current + $window);
    $pageSizes = \App\Support\AdminPagination::PAGE_SIZES;

    $linkClasses = 'inline-flex h-8 min-w-8 items-center justify-center rounded-lg border px-2.5 text-xs font-medium transition';
    $idleClasses = 'border-slate-200 bg-white text-slate-600 hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700';
    $activeClasses = 'border-fuchsia-600 bg-fuchsia-600 text-white';
    $disabledClasses = 'border-slate-100 bg-slate-50 text-slate-300';
@endphp

{{-- Reusable admin pagination (spec §28.4): range + total, page-size picker,
     First / Previous / numbers / Next / Last. Links keep the query string. --}}
<div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
        <span>
            @if ($paginator->total() > 0)
                Showing <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                of <span class="font-semibold text-slate-700">{{ number_format($paginator->total()) }}</span>
            @else
                No records
            @endif
        </span>

        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-1.5">
            @foreach (request()->except(['per_page', 'page']) as $key => $value)
                @if (is_scalar($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="per-page" class="text-slate-500">Rows</label>
            <select id="per-page" name="per_page" onchange="this.form.submit()"
                class="rounded-lg border border-slate-300 bg-white py-1 pl-2 pr-7 text-xs focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($pageSizes as $size)
                    <option value="{{ $size }}" @selected($paginator->perPage() === $size)>{{ $size }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="rounded border border-slate-300 px-2 py-1">Go</button></noscript>
        </form>
    </div>

    @if ($last > 1)
        <nav class="flex flex-wrap items-center gap-1" aria-label="Pagination">
            @if ($current > 1)
                <a href="{{ $paginator->url(1) }}" class="{{ $linkClasses }} {{ $idleClasses }}" aria-label="First page">«</a>
                <a href="{{ $paginator->previousPageUrl() }}" class="{{ $linkClasses }} {{ $idleClasses }}" rel="prev" aria-label="Previous page">‹</a>
            @else
                <span class="{{ $linkClasses }} {{ $disabledClasses }}" aria-hidden="true">«</span>
                <span class="{{ $linkClasses }} {{ $disabledClasses }}" aria-hidden="true">‹</span>
            @endif

            @if ($from > 1)
                <span class="px-1 text-xs text-slate-400">…</span>
            @endif

            @for ($page = $from; $page <= $to; $page++)
                @if ($page === $current)
                    <span class="{{ $linkClasses }} {{ $activeClasses }}" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="{{ $linkClasses }} {{ $idleClasses }}">{{ $page }}</a>
                @endif
            @endfor

            @if ($to < $last)
                <span class="px-1 text-xs text-slate-400">…</span>
            @endif

            @if ($current < $last)
                <a href="{{ $paginator->nextPageUrl() }}" class="{{ $linkClasses }} {{ $idleClasses }}" rel="next" aria-label="Next page">›</a>
                <a href="{{ $paginator->url($last) }}" class="{{ $linkClasses }} {{ $idleClasses }}" aria-label="Last page">»</a>
            @else
                <span class="{{ $linkClasses }} {{ $disabledClasses }}" aria-hidden="true">›</span>
                <span class="{{ $linkClasses }} {{ $disabledClasses }}" aria-hidden="true">»</span>
            @endif
        </nav>
    @endif
</div>
