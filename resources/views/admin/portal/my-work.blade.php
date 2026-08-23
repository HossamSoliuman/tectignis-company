@extends('layouts.admin')

@section('title', 'My Work')

@php
    // Mobile-first: this is the screen staff open on a phone, so every group is
    // a single stacked column of large touch targets rather than a table.
    $groups = [
        ['label' => 'Overdue', 'tasks' => $overdue, 'tone' => 'rose', 'icon' => 'clock'],
        ['label' => 'Due Today', 'tasks' => $today, 'tone' => 'amber', 'icon' => 'calendar'],
        ['label' => 'Upcoming', 'tasks' => $upcoming, 'tone' => 'sky', 'icon' => 'trending-up'],
        ['label' => 'No Deadline', 'tasks' => $undated, 'tone' => 'slate', 'icon' => 'clipboard-list'],
    ];
@endphp

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">My Work</h2>
            <p class="text-sm text-slate-500">{{ $employee->name }} · {{ now()->format('D, d M Y') }}</p>
        </div>
        <a href="{{ route('admin.portal.daily-work.create') }}"
            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700 sm:w-auto">
            <x-admin.icon name="plus" class="h-4 w-4" /> Log Today's Work
        </a>
    </div>

    @if ($loggedToday->isEmpty())
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <x-admin.icon name="clock" class="h-5 w-5 shrink-0 text-amber-500" />
            <span>You have not submitted a daily work update today.</span>
        </div>
    @else
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <span class="font-medium">{{ $loggedToday->count() }}</span>
            {{ Str::plural('activity', $loggedToday->count()) }} logged today.
            <a href="{{ route('admin.portal.daily-work.index') }}" class="font-medium underline">View</a>
        </div>
    @endif

    <div class="space-y-5">
        @foreach ($groups as $group)
            @continue($group['tasks']->isEmpty())
            <section>
                <h3 class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <x-admin.icon :name="$group['icon']" class="h-4 w-4 text-slate-400" />
                    {{ $group['label'] }}
                    <x-portal.badge :color="$group['tone']" :label="$group['tasks']->count()" :dot="false" />
                </h3>
                <ul class="space-y-2">
                    @foreach ($group['tasks'] as $task)
                        <li>
                            <a href="{{ route('admin.portal.tasks.show', $task) }}"
                                class="block rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-fuchsia-300 hover:shadow-md">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-medium text-slate-800">{{ $task->title }}</p>
                                    <x-portal.badge :color="$task->priority->badgeColor()" :label="$task->priority->label()" class="shrink-0" />
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                                    <span>{{ $task->code }}</span>
                                    <x-portal.badge :color="$task->status->badgeColor()" :label="$task->status->label()" />
                                    <x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" />
                                </div>
                                <x-portal.progress-bar :value="$task->progress" class="mt-3" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        @if ($overdue->isEmpty() && $today->isEmpty() && $upcoming->isEmpty() && $undated->isEmpty())
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <x-portal.empty-state message="No open tasks assigned to you." />
            </div>
        @endif
    </div>
@endsection
