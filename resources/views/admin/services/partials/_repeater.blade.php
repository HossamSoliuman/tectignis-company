{{-- Generic add/remove/reorder repeater.
     Params:
       $prefix   — base input name, e.g. "content[sub_services][items]"
       $rows     — existing rows (list of arrays or strings)
       $rowView  — partial used to render one row
       $empty    — the "blank row" value used in the add template ([] or '')
       $extra    — extra vars forwarded to every row include
       $addLabel — text on the add button --}}
@php
    $rows = $rows ?? [];
    $empty = $empty ?? [];
    $extra = $extra ?? [];
@endphp

<div data-repeater class="space-y-3">
    <div data-repeater-rows class="space-y-3">
        @foreach ($rows as $index => $item)
            @include($rowView, array_merge(['prefix' => $prefix, 'index' => $index, 'item' => $item], $extra))
        @endforeach
    </div>

    <template data-repeater-template>
        @include($rowView, array_merge(['prefix' => $prefix, 'index' => '__INDEX__', 'item' => $empty], $extra))
    </template>

    <button type="button" data-repeater-add
        class="inline-flex items-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-fuchsia-300 hover:text-fuchsia-600">
        <x-admin.icon name="plus" class="h-4 w-4" /> {{ $addLabel ?? 'Add row' }}
    </button>
</div>
