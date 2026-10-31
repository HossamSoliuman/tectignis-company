{{-- One picked project example: a case study dropdown with the shared reorder / remove controls. --}}
@php $selectedId = is_numeric($item ?? null) ? (int) $item : null; @endphp

<div data-repeater-row class="flex items-center gap-3">
    <select name="{{ $prefix }}[{{ $index }}]" aria-label="Case study"
        class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <option value="">— Select —</option>
        @foreach ($options as $option)
            <option value="{{ $option->id }}" @selected($selectedId === $option->id)>{{ $option->title }}</option>
        @endforeach
    </select>
    @include('admin.services.partials._row-controls')
</div>
