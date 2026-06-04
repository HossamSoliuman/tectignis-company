{{-- FAQ row: question + answer. --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    <div class="flex-1 space-y-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][question]" value="{{ data_get($item, 'question') }}"
            placeholder="Question"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <textarea name="{{ $prefix }}[{{ $index }}][answer]" rows="2"
            placeholder="Answer"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ data_get($item, 'answer') }}</textarea>
    </div>
    @include('admin.services.partials._row-controls')
</div>
