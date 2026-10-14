{{-- Problem row: customer problem + how the service solves it. --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    <div class="flex-1 space-y-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][problem]" value="{{ data_get($item, 'problem') }}"
            placeholder="Customer problem"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <textarea name="{{ $prefix }}[{{ $index }}][solution]" rows="2"
            placeholder="How we solve it"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ data_get($item, 'solution') }}</textarea>
    </div>
    @include('admin.services.partials._row-controls')
</div>
