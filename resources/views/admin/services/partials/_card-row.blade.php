{{-- Card row: icon + title + description (sub-services cards, process steps). --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    @include('admin.services.partials._item-icon', ['prefix' => $prefix, 'index' => $index, 'icon' => data_get($item, 'icon', '')])
    <div class="flex-1 space-y-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][title]" value="{{ data_get($item, 'title') }}"
            placeholder="{{ $titlePlaceholder ?? 'Title' }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <textarea name="{{ $prefix }}[{{ $index }}][description]" rows="2"
            placeholder="Short description"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ data_get($item, 'description') }}</textarea>
    </div>
    @include('admin.services.partials._row-controls')
</div>
