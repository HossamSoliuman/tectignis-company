@extends('layouts.admin')

@section('title', $task->code)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.tasks.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to tasks
        </a>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $task->code }}</p>
                <h2 class="text-lg font-semibold text-slate-900">{{ $task->title }}</h2>
                @if ($task->description)
                    <p class="mt-2 max-w-2xl whitespace-pre-line text-sm text-slate-600">{{ $task->description }}</p>
                @endif
            </div>
            <div class="flex shrink-0 items-center gap-1">
                @can('update', $task)
                    <x-admin.edit-link :href="route('admin.portal.tasks.edit', $task)" />
                @endcan
                @can('delete', $task)
                    <x-admin.delete-button :action="route('admin.portal.tasks.destroy', $task)" confirm="Delete this task?" />
                @endcan
            </div>
        </div>

        <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-sm sm:grid-cols-4">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Assigned To</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $task->assignee?->name ?? 'Unassigned' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Raised By</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $task->creator?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Priority</dt>
                <dd class="mt-0.5"><x-portal.badge :color="$task->priority->badgeColor()" :label="$task->priority->label()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Status</dt>
                <dd class="mt-0.5"><x-portal.badge :color="$task->status->badgeColor()" :label="$task->status->label()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Start</dt>
                <dd class="mt-0.5 text-slate-700">{{ $task->start_date?->format('d M Y, g:i A') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Deadline</dt>
                <dd class="mt-0.5"><x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Progress</dt>
                <dd class="mt-1"><x-portal.progress-bar :value="$task->progress" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Reopened</dt>
                <dd class="mt-0.5 text-slate-700">{{ $task->reopened_count }}×</dd>
            </div>
        </dl>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            @can('update', $task)
                <x-portal.panel title="Log an Update" icon="pencil">
                    <form action="{{ route('admin.portal.tasks.updates.store', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">What happened? *</label>
                            <textarea name="note" rows="3" required placeholder="Progress, blockers, next step…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400"></textarea>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Progress (%)</label>
                                <input type="number" name="progress" min="0" max="100" value="{{ $task->progress }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($task->status->value === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Attachment</label>
                                <input type="file" name="attachment"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-sm file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs file:font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Log Update</button>
                        </div>
                    </form>
                </x-portal.panel>
            @endcan

            <x-portal.panel title="Timeline" icon="clock" :count="$task->updates->count()">
                <ol class="divide-y divide-slate-100">
                    @forelse ($task->updates->sortByDesc('logged_at') as $update)
                        <li class="px-4 py-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-slate-800">{{ $update->employee?->name ?? 'System' }}</p>
                                <span class="text-xs text-slate-400">{{ $update->logged_at?->format('d M Y, g:i A') }}</span>
                            </div>
                            <p class="mt-1 whitespace-pre-line text-sm text-slate-600">{{ $update->note }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                @if ($update->status_from && $update->status_to && $update->status_from !== $update->status_to)
                                    <x-portal.badge :color="$update->status_from->badgeColor()" :label="$update->status_from->label()" />
                                    <span class="text-slate-400">→</span>
                                    <x-portal.badge :color="$update->status_to->badgeColor()" :label="$update->status_to->label()" />
                                @endif
                                @if (! is_null($update->progress))
                                    <span class="text-slate-400">{{ $update->progress }}% complete</span>
                                @endif
                                @foreach ($update->attachments as $attachment)
                                    <a href="{{ route('admin.portal.files.show', $attachment) }}" class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                        <x-admin.icon name="paper-clip" class="h-3.5 w-3.5" /> {{ $attachment->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="No updates logged yet." icon="clock" /></li>
                    @endforelse
                </ol>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            <x-portal.panel title="Attachments" icon="paper-clip" :count="$task->attachments->count()">
                <ul class="divide-y divide-slate-100">
                    @forelse ($task->attachments as $attachment)
                        <li class="flex items-center justify-between gap-3 px-4 py-3">
                            <a href="{{ route('admin.portal.files.show', $attachment) }}" class="min-w-0 truncate text-sm font-medium text-slate-700 hover:text-fuchsia-700">
                                {{ $attachment->original_name }}
                            </a>
                            <span class="shrink-0 text-xs text-slate-400">{{ $attachment->humanSize() }}</span>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="No documents attached." icon="paper-clip" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>

            <x-portal.panel title="Record" icon="shield-check">
                <dl class="space-y-2 px-4 py-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Created</dt>
                        <dd class="text-slate-700">{{ $task->created_at?->format('d M Y, g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Last change</dt>
                        <dd class="text-slate-700">{{ $task->updated_at?->diffForHumans() }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Completed</dt>
                        <dd class="text-slate-700">{{ $task->completed_at?->format('d M Y, g:i A') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-portal.panel>
        </div>
    </div>
@endsection
