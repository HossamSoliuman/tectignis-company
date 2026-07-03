{{-- Careers benefit card row: Font Awesome icon class + title + text. --}}
@php $item = is_array($item ?? null) ? $item : []; @endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    <div class="flex-1 space-y-2">
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <input type="text" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ data_get($item, 'icon') }}"
                placeholder="Icon class e.g. fas fa-chart-line"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <input type="text" name="{{ $prefix }}[{{ $index }}][title]" value="{{ data_get($item, 'title') }}"
                placeholder="Title e.g. Growth"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <textarea name="{{ $prefix }}[{{ $index }}][text]" rows="2"
            placeholder="Short description"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ data_get($item, 'text') }}</textarea>
    </div>
    @include('admin.services.partials._row-controls')
</div>
