{{-- Single free-text repeater row (hero bullets, why-choose points). --}}
@php $value = is_array($item ?? null) ? '' : ($item ?? ''); @endphp

<div data-repeater-row class="flex items-center gap-3">
    <input type="text" name="{{ $prefix }}[{{ $index }}]" value="{{ $value }}"
        placeholder="{{ $placeholder ?? 'Enter text' }}"
        class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    @include('admin.services.partials._row-controls')
</div>
