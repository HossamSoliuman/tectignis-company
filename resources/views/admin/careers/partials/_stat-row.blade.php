{{-- Careers stat row: Font Awesome icon class + value + label. --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    <div class="flex-1 grid grid-cols-1 gap-2 md:grid-cols-3">
        <input type="text" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ data_get($item, 'icon') }}"
            placeholder="Icon class e.g. fas fa-users"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <input type="text" name="{{ $prefix }}[{{ $index }}][value]" value="{{ data_get($item, 'value') }}"
            placeholder="Number e.g. 200+"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <input type="text" name="{{ $prefix }}[{{ $index }}][label]" value="{{ data_get($item, 'label') }}"
            placeholder="Label e.g. Team Members"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    @include('admin.services.partials._row-controls')
</div>
