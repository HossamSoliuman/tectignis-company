{{-- Careers stat row: icon, then either a linked company figure (value read
     from Stats) or a custom value; the label is optional for linked rows. --}}
@php
    $item = is_array($item ?? null) ? $item : [];
    $companyStats = $companyStats ?? collect();
    $control = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

<div data-repeater-row class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
    <div class="grid min-w-0 flex-1 grid-cols-1 gap-2 md:grid-cols-4">
        <input type="text" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ data_get($item, 'icon') }}"
            placeholder="Icon class e.g. fas fa-users" aria-label="Icon class"
            class="{{ $control }} font-mono">
        <select name="{{ $prefix }}[{{ $index }}][stat]" aria-label="Company figure" class="{{ $control }}">
            <option value="">Custom value</option>
            @foreach ($companyStats as $companyStat)
                <option value="{{ $companyStat->key }}" @selected(data_get($item, 'stat') === $companyStat->key)>
                    {{ $companyStat->label }} ({{ $companyStat->value }}){{ $companyStat->is_active ? '' : ' · off' }}
                </option>
            @endforeach
        </select>
        <input type="text" name="{{ $prefix }}[{{ $index }}][value]" value="{{ data_get($item, 'value') }}"
            placeholder="Value, when custom" aria-label="Value"
            class="{{ $control }}">
        <input type="text" name="{{ $prefix }}[{{ $index }}][label]" value="{{ data_get($item, 'label') }}"
            placeholder="Label" aria-label="Label"
            class="{{ $control }}">
    </div>
    @include('admin.services.partials._row-controls')
</div>
