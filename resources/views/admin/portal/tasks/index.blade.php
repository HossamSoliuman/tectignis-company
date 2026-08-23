@extends('layouts.admin')

@section('title', 'Tasks')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="clipboard-list" class="h-5 w-5 text-fuchsia-600" />
            Tasks
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $tasks->total() }}</span>
        </h2>
        @can('create', \App\Models\Portal\Task::class)
            <a href="{{ route('admin.portal.tasks.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Task
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('admin.portal.tasks.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Code, title or description…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Priority</label>
            <select name="priority" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($priorities as $value => $label)
                    <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Employee</label>
            <select name="employee" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Anyone</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) request('employee') === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Due from</label>
            <input type="date" name="due_from" value="{{ request('due_from') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Due to</label>
            <input type="date" name="due_to" value="{{ request('due_to') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-4">
            <input type="hidden" name="overdue" value="0">
            <input type="checkbox" id="overdue" name="overdue" value="1" @checked(request()->boolean('overdue'))
                class="rounded border-slate-300">
            <label for="overdue" class="text-sm font-medium text-slate-700">Overdue only</label>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2">
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                <x-admin.icon name="search" class="h-4 w-4" /> Filter
            </button>
            <a href="{{ route('admin.portal.tasks.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Task</th>
                        <th class="px-4 py-3">Assigned To</th>
                        <th class="px-4 py-3">Priority</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Progress</th>
                        <th class="px-4 py-3">Due</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tasks as $task)
                        <tr class="transition hover:bg-slate-50 {{ $task->isOverdue() ? 'bg-rose-50/40' : '' }}">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.portal.tasks.show', $task) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $task->title }}</a>
                                <p class="text-xs text-slate-400">{{ $task->code }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $task->assignee?->name ?? '—' }}</td>
                            <td class="px-4 py-3"><x-portal.badge :color="$task->priority->badgeColor()" :label="$task->priority->label()" /></td>
                            <td class="px-4 py-3"><x-portal.badge :color="$task->status->badgeColor()" :label="$task->status->label()" /></td>
                            <td class="px-4 py-3 w-32"><x-portal.progress-bar :value="$task->progress" /></td>
                            <td class="px-4 py-3"><x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $task)
                                        <x-admin.edit-link :href="route('admin.portal.tasks.edit', $task)" />
                                    @endcan
                                    @can('delete', $task)
                                        <x-admin.delete-button :action="route('admin.portal.tasks.destroy', $task)" confirm="Delete this task?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"><x-portal.empty-state message="No tasks match these filters." icon="clipboard-list" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $tasks->links() }}</div>
@endsection
