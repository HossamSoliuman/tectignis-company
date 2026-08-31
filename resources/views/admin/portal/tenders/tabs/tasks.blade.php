@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Tender Tasks" icon="clipboard-list" :count="$tasks->count()">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Task</th>
                                <th class="px-4 py-3">Owner</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Progress</th>
                                <th class="px-4 py-3">Due</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tasks as $task)
                                <tr class="{{ $task->isOverdue() ? 'bg-rose-50/40' : '' }}">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.portal.tasks.show', $task) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $task->title }}</a>
                                        <p class="text-xs text-slate-400">{{ $task->code }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">{{ $task->assignee?->name ?? 'Unassigned' }}</td>
                                    <td class="px-4 py-3"><x-portal.badge :color="$task->status->badgeColor()" :label="$task->status->label()" /></td>
                                    <td class="w-32 px-4 py-3"><x-portal.progress-bar :value="$task->progress" /></td>
                                    <td class="px-4 py-3"><x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"><x-portal.empty-state message="No tasks on this tender yet." icon="clipboard-list" /></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            @can('update', $tender)
                <x-portal.panel title="Add a Task" icon="plus">
                    <form action="{{ route('admin.portal.tenders.tasks.store', $tender) }}" method="POST" class="space-y-3 p-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Title *</label>
                            <input type="text" name="title" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="description" rows="2"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400"></textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Assign to</label>
                            <select name="assigned_to_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                <option value="">Unassigned</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected($tender->assigned_employee_id === $employee->id)>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Priority *</label>
                                <select name="priority" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @foreach ($priorities as $value => $label)
                                        <option value="{{ $value }}" @selected($value === 'medium')>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Due</label>
                                <input type="datetime-local" name="due_date"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Add Task</button>
                    </form>
                </x-portal.panel>

                @if ($tasks->isEmpty())
                    <x-portal.panel title="Standard Tender Steps" icon="light-bulb">
                        <div class="space-y-3 p-4">
                            <p class="text-sm text-slate-600">
                                This tender has no tasks. The standard twelve steps — download, study, eligibility,
                                documents, OEM, technical, commercial, review, upload — can be created and
                                back-scheduled from the deadline.
                            </p>
                            <form action="{{ route('admin.portal.tenders.tasks.template', $tender) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-lg border border-fuchsia-200 bg-fuchsia-50 px-3 py-2 text-sm font-medium text-fuchsia-700 transition hover:bg-fuchsia-100">
                                    Create the standard tasks
                                </button>
                            </form>
                        </div>
                    </x-portal.panel>
                @endif
            @endcan
        </div>
    </div>
@endsection
