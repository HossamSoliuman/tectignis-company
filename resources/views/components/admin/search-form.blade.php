@props(['placeholder' => 'Search…'])

{{-- Server-side search (spec §28.4). Keeps every other query parameter
     (filters, sort, page size) and returns to page 1. --}}
<form method="GET" action="{{ url()->current() }}" class="relative" role="search">
    @foreach (request()->except(['q', 'page']) as $key => $value)
        @if (is_scalar($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}"
        class="w-48 rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm transition focus:w-64 focus:outline-none focus:ring-2 focus:ring-fuchsia-400 sm:w-56">
</form>
