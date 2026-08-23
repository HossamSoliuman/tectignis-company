@props(['color' => 'slate', 'label' => null, 'dot' => true])

@php
    $palette = [
        'slate' => 'bg-slate-100 text-slate-600',
        'sky' => 'bg-sky-100 text-sky-700',
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'rose' => 'bg-rose-100 text-rose-700',
        'violet' => 'bg-violet-100 text-violet-700',
        'indigo' => 'bg-indigo-100 text-indigo-700',
    ];
    $dots = [
        'slate' => 'bg-slate-400',
        'sky' => 'bg-sky-500',
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'rose' => 'bg-rose-500',
        'violet' => 'bg-violet-500',
        'indigo' => 'bg-indigo-500',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium '.($palette[$color] ?? $palette['slate'])]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dots[$color] ?? $dots['slate'] }}"></span>
    @endif
    {{ $label ?? $slot }}
</span>
