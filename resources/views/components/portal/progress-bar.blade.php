@props(['value' => 0, 'showLabel' => true])

@php $percent = max(0, min(100, (int) $value)); @endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <div class="h-1.5 w-full min-w-16 overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full bg-gradient-to-r from-fuchsia-500 to-purple-600" style="width: {{ $percent }}%"></div>
    </div>
    @if ($showLabel)
        <span class="shrink-0 text-xs font-medium text-slate-500">{{ $percent }}%</span>
    @endif
</div>
