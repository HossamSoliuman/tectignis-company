@props(['target', 'placeholder' => 'Search…'])

<div class="relative w-full sm:w-auto">
    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
    <input type="search" data-table-search="{{ $target }}" placeholder="{{ $placeholder }}"
        class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm transition focus:outline-none focus:ring-2 focus:ring-fuchsia-400 sm:w-56 sm:focus:w-64">
</div>
