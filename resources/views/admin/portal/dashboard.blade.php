@extends('layouts.admin')

@section('title', 'Portal Dashboard')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                <x-admin.icon name="chart-bar" class="h-5 w-5 text-fuchsia-600" />
                {{ $manages ? 'Operations Overview' : 'My Overview' }}
            </h2>
            <p class="text-sm text-slate-500">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.portal.daily-work.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> Daily Update
            </a>
            @can('create', \App\Models\Portal\Task::class)
                <a href="{{ route('admin.portal.tasks.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-fuchsia-300 hover:text-fuchsia-700">
                    <x-admin.icon name="plus" class="h-4 w-4" /> New Task
                </a>
            @endcan
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-portal.kpi-card label="Open Tasks" :value="$counts['open']" icon="clipboard-list" tone="sky"
            :href="route('admin.portal.tasks.index', ['status' => \App\Enums\Portal\TaskStatus::InProgress->value])" />
        <x-portal.kpi-card label="Overdue" :value="$counts['overdue']" icon="clock" tone="rose"
            :href="route('admin.portal.tasks.index', ['overdue' => 1])" />
        <x-portal.kpi-card label="Due Today" :value="$counts['due_today']" icon="calendar" tone="amber"
            :href="route('admin.portal.my-work')" />
        <x-portal.kpi-card label="Completed" :value="$counts['completed']" icon="check-circle" tone="emerald"
            :href="route('admin.portal.tasks.index', ['status' => \App\Enums\Portal\TaskStatus::Completed->value])" />
    </div>

    {{-- Modules arriving in later phases. Shown as placeholders so the shape of
         the finished dashboard is visible, never as fake numbers. --}}
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([['Active Tenders', 'briefcase', 'Phase 2'], ['OEM Follow-ups Due', 'share', 'Phase 2'], ['Sales Pipeline', 'trending-up', 'Phase 3']] as [$label, $icon, $phase])
            <div class="flex items-center gap-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/60 p-4">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-slate-300 ring-1 ring-slate-200">
                    <x-admin.icon :name="$icon" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $label }}</p>
                    <p class="text-sm font-medium text-slate-400">Arrives in {{ $phase }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <x-portal.panel title="Overdue Tasks" icon="clock" :count="$overdueTasks->count()"
            :action="route('admin.portal.tasks.index', ['overdue' => 1])">
            <ul class="divide-y divide-slate-100">
                @forelse ($overdueTasks as $task)
                    <li class="flex items-center justify-between gap-3 px-4 py-3">
                        <div class="min-w-0">
                            <a href="{{ route('admin.portal.tasks.show', $task) }}" class="block truncate text-sm font-medium text-slate-800 hover:text-fuchsia-700">
                                {{ $task->title }}
                            </a>
                            <p class="truncate text-xs text-slate-400">
                                {{ $task->code }} · {{ $task->assignee?->name ?? 'Unassigned' }}
                            </p>
                        </div>
                        <x-portal.due-date :date="$task->due_date" class="shrink-0 text-right" />
                    </li>
                @empty
                    <li><x-portal.empty-state message="Nothing is overdue. Well done." /></li>
                @endforelse
            </ul>
        </x-portal.panel>

        <x-portal.panel title="Due Soon" icon="calendar" :count="$dueSoonTasks->count()"
            :action="route('admin.portal.my-work')" action-label="My work">
            <ul class="divide-y divide-slate-100">
                @forelse ($dueSoonTasks as $task)
                    <li class="flex items-center justify-between gap-3 px-4 py-3">
                        <div class="min-w-0">
                            <a href="{{ route('admin.portal.tasks.show', $task) }}" class="block truncate text-sm font-medium text-slate-800 hover:text-fuchsia-700">
                                {{ $task->title }}
                            </a>
                            <p class="truncate text-xs text-slate-400">
                                {{ $task->code }} · {{ $task->assignee?->name ?? 'Unassigned' }}
                            </p>
                        </div>
                        <x-portal.due-date :date="$task->due_date" class="shrink-0 text-right" />
                    </li>
                @empty
                    <li><x-portal.empty-state message="No deadlines in the next few days." icon="calendar" /></li>
                @endforelse
            </ul>
        </x-portal.panel>

        <x-portal.panel title="Today's Work" icon="document-text" :count="$todaysWork->count()"
            :action="route('admin.portal.daily-work.index')">
            <ul class="divide-y divide-slate-100">
                @forelse ($todaysWork as $update)
                    <li class="px-4 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm text-slate-700">{{ $update->activity }}</p>
                            <x-portal.badge :color="$update->status->badgeColor()" :label="$update->status->label()" class="shrink-0" />
                        </div>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $update->employee?->name }} · {{ $update->related_category->label() }}
                            @if ($update->start_time && $update->end_time)
                                · {{ $update->start_time }}–{{ $update->end_time }}
                            @endif
                        </p>
                    </li>
                @empty
                    <li><x-portal.empty-state message="No work logged yet today." icon="document-text" /></li>
                @endforelse
            </ul>
        </x-portal.panel>

        @if ($manages)
            <x-portal.panel title="Employee Work Status" icon="users" :count="$employeeStatus->count()"
                :action="route('admin.portal.employees.index')">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-2">Employee</th>
                                <th class="px-4 py-2">Today</th>
                                <th class="px-4 py-2">Open</th>
                                <th class="px-4 py-2">Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($employeeStatus as $person)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.portal.employees.show', $person) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $person->name }}</a>
                                        <p class="text-xs text-slate-400">{{ $person->department?->name ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($person->todays_updates_count > 0)
                                            <x-portal.badge color="emerald" label="Reported" />
                                        @else
                                            <x-portal.badge color="amber" label="No update" />
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-slate-600">{{ $person->open_tasks_count }}</td>
                                    <td class="px-4 py-2 {{ $person->overdue_tasks_count > 0 ? 'font-semibold text-rose-600' : 'text-slate-400' }}">
                                        {{ $person->overdue_tasks_count }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><x-portal.empty-state message="No active employees yet." icon="users" /></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-portal.panel>

            <x-portal.panel title="Recent Activity" icon="shield-check" :count="$recentActivity->count()" class="lg:col-span-2">
                <ul class="divide-y divide-slate-100">
                    @forelse ($recentActivity as $entry)
                        <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                            <p class="min-w-0 truncate text-sm text-slate-600">
                                <span class="font-medium text-slate-800">{{ $entry->user?->name ?? 'System' }}</span>
                                — {{ $entry->description }}
                            </p>
                            <span class="shrink-0 text-xs text-slate-400">{{ $entry->created_at?->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="No activity recorded yet." icon="shield-check" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>
        @endif
    </div>
@endsection
