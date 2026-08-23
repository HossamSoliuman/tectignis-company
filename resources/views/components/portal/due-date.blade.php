@props(['date' => null, 'closed' => false])

@php
    $overdue = $date && ! $closed && $date->isPast();
    $dueToday = $date && ! $closed && ! $overdue && $date->isToday();
@endphp

@if (! $date)
    <span class="text-xs text-slate-400">No deadline</span>
@else
    <span class="inline-flex flex-col leading-tight">
        <span class="text-sm {{ $overdue ? 'font-semibold text-rose-600' : ($dueToday ? 'font-semibold text-amber-600' : 'text-slate-700') }}">
            {{ $date->format('d M Y, g:i A') }}
        </span>
        <span class="text-xs {{ $overdue ? 'text-rose-500' : 'text-slate-400' }}">
            @if ($overdue)
                {{ $date->diffForHumans(['parts' => 2, 'short' => true]) }} late
            @elseif ($closed)
                &mdash;
            @else
                {{ $date->diffForHumans(['parts' => 2, 'short' => true]) }}
            @endif
        </span>
    </span>
@endif
