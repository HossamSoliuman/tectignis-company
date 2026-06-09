{{-- Stat row: icon + value + label (performance stats). --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    @include('admin.services.partials._item-icon', ['prefix' => $prefix, 'index' => $index, 'icon' => data_get($item, 'icon', '')])
    <div class="flex-1 grid grid-cols-2 gap-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][value]" value="{{ data_get($item, 'value') }}"
            placeholder="Value (e.g. 250+)"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <input type="text" name="{{ $prefix }}[{{ $index }}][label]" value="{{ data_get($item, 'label') }}"
            placeholder="Label (e.g. Corporate Clients)"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    @include('admin.services.partials._row-controls')
</div>
