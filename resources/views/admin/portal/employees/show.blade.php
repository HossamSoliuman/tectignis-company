@extends('layouts.admin')

@section('title', $employee->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.employees.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to employees
        </a>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-lg font-semibold text-white">
                    {{ Str::upper(Str::substr($employee->name, 0, 1)) }}
                </span>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $employee->name }}</h2>
                    <p class="text-sm text-slate-500">
                        {{ $employee->employee_code }} · {{ $employee->designation ?? 'No designation' }} · {{ $employee->department?->name ?? 'No department' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-portal.badge :color="$employee->status->badgeColor()" :label="$employee->status->label()" />
                @if ($role = $employee->user?->portalRole())
                    <x-portal.badge :color="$role->badgeColor()" :label="$role->label()" />
                @endif
                @can('update', $employee)
                    <x-admin.edit-link :href="route('admin.portal.employees.edit', $employee)" />
                @endcan
            </div>
        </div>

        <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-sm sm:grid-cols-4">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Email</dt>
                <dd class="mt-0.5 truncate text-slate-700">{{ $employee->email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Phone</dt>
                <dd class="mt-0.5 text-slate-700">{{ $employee->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Joined</dt>
                <dd class="mt-0.5 text-slate-700">{{ $employee->date_of_joining?->format('d M Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Reports To</dt>
                <dd class="mt-0.5 text-slate-700">{{ $employee->manager?->name ?? '—' }}</dd>
            </div>
        </dl>

        @if ($employee->notes)
            <p class="mt-4 whitespace-pre-line rounded-lg bg-slate-50 p-3 text-sm text-slate-600">{{ $employee->notes }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <x-portal.panel title="Open Tasks" icon="clipboard-list" :count="$openTasks->count()"
            :action="route('admin.portal.tasks.index', ['employee' => $employee->id])">
            <ul class="divide-y divide-slate-100">
                @forelse ($openTasks as $task)
                    <li class="flex items-center justify-between gap-3 px-4 py-3">
                        <div class="min-w-0">
                            <a href="{{ route('admin.portal.tasks.show', $task) }}" class="block truncate text-sm font-medium text-slate-800 hover:text-fuchsia-700">{{ $task->title }}</a>
                            <p class="text-xs text-slate-400">{{ $task->code }}</p>
                        </div>
                        <x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" class="shrink-0 text-right" />
                    </li>
                @empty
                    <li><x-portal.empty-state message="No open tasks." icon="clipboard-list" /></li>
                @endforelse
            </ul>
        </x-portal.panel>

        <x-portal.panel title="Recent Daily Work" icon="calendar" :count="$recentWork->count()"
            :action="route('admin.portal.daily-work.index', ['employee' => $employee->id])">
            <ul class="divide-y divide-slate-100">
                @forelse ($recentWork as $work)
                    <li class="px-4 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm text-slate-700">{{ $work->activity }}</p>
                            <span class="shrink-0 text-xs text-slate-400">{{ $work->work_date->format('d M') }}</span>
                        </div>
                        <div class="mt-1 flex items-center gap-2">
                            <x-portal.badge :color="$work->related_category->badgeColor()" :label="$work->related_category->label()" />
                            <x-portal.badge :color="$work->status->badgeColor()" :label="$work->status->label()" />
                        </div>
                    </li>
                @empty
                    <li><x-portal.empty-state message="No daily work logged yet." icon="calendar" /></li>
                @endforelse
            </ul>
        </x-portal.panel>

        @if ($employee->directReports->isNotEmpty())
            <x-portal.panel title="Direct Reports" icon="users" :count="$employee->directReports->count()" class="lg:col-span-2">
                <ul class="divide-y divide-slate-100">
                    @foreach ($employee->directReports as $report)
                        <li class="flex items-center justify-between gap-3 px-4 py-3">
                            <a href="{{ route('admin.portal.employees.show', $report) }}" class="text-sm font-medium text-slate-800 hover:text-fuchsia-700">{{ $report->name }}</a>
                            <span class="text-xs text-slate-400">{{ $report->designation ?? '—' }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-portal.panel>
        @endif
    </div>
@endsection
