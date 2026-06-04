{{-- Feature/trust-strip row: icon + short label. --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    @include('admin.services.partials._item-icon', ['prefix' => $prefix, 'index' => $index, 'icon' => data_get($item, 'icon', '')])
    <input type="text" name="{{ $prefix }}[{{ $index }}][label]" value="{{ data_get($item, 'label') }}"
        placeholder="Label (e.g. On-Time Delivery)"
        class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    @include('admin.services.partials._row-controls')
</div>
