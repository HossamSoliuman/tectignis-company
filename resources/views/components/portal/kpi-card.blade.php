@props(['label', 'value', 'icon' => 'grid', 'tone' => 'slate', 'href' => null, 'hint' => null])

@php
    $tones = [
        'slate' => 'from-slate-500 to-slate-600 shadow-slate-500/25',
        'sky' => 'from-sky-500 to-blue-600 shadow-blue-500/25',
        'emerald' => 'from-emerald-500 to-teal-600 shadow-emerald-500/25',
        'amber' => 'from-amber-500 to-orange-500 shadow-amber-500/25',
        'rose' => 'from-rose-500 to-red-600 shadow-rose-500/25',
        'violet' => 'from-fuchsia-500 to-purple-600 shadow-purple-600/25',
    ];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition {{ $href ? 'hover:border-fuchsia-300 hover:shadow-md' : '' }}">
    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $tones[$tone] ?? $tones['slate'] }}">
        <x-admin.icon :name="$icon" class="h-5 w-5" />
    </span>
    <div class="min-w-0">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
        <p class="text-2xl font-semibold leading-tight text-slate-900">{{ $value }}</p>
        @if ($hint)
            <p class="truncate text-xs text-slate-400">{{ $hint }}</p>
        @endif
    </div>
</{{ $tag }}>
