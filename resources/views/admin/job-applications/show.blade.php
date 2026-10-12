@extends('layouts.admin')

@section('title', 'Application '.$application->reference())

@section('content')
    @php
        $canUpdate = auth()->user()->can('update', $application);
        $timezone = config('app.timezone');
        $isArchived = $application->isArchived();
        $details = [
            ['envelope', 'Email', $application->email, 'mailto:'.$application->email],
            ['phone', 'Phone', $application->phone, 'tel:'.preg_replace('/[^+\d]/', '', $application->phone)],
            ['globe', 'Current location', $application->location, null],
            ['chart-bar', 'Total experience', $application->experienceLabel(), null],
            ['user', 'Current role', $application->current_role, null],
            ['clock', 'Notice period', $application->notice_period, null],
            ['link', 'LinkedIn', $application->linkedin_url, $application->linkedin_url],
            ['external-link', 'Portfolio / GitHub', $application->portfolio_url, $application->portfolio_url],
            ['calendar', 'Received', $application->created_at->timezone($timezone)->format('M d, Y H:i T'), null],
            ['shield-check', 'Consent', $application->consented_at?->timezone($timezone)->format('M d, Y H:i T'), null],
        ];
        $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    @endphp

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.job-applications.index', $isArchived ? ['archived' => 1] : []) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Applications
        </a>
        @if ($isArchived)
            <div class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                Archived {{ $application->archived_at->timezone($timezone)->format('M d, Y') }}.
                @if ($canUpdate)
                    <form action="{{ route('admin.job-applications.unarchive', $application) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="font-semibold underline">Restore</button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        {{-- Candidate --}}
        <div class="space-y-5 xl:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 pb-5">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-lg font-semibold text-white">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($application->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $application->name }}</h2>
                        <p class="text-xs text-slate-400">{{ $application->reference() }} · {{ $application->appliedFor() }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
                </div>

                <dl class="grid grid-cols-1 gap-4 border-b border-slate-100 py-5 text-sm sm:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-admin.icon name="briefcase" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                        <div class="min-w-0">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Position</dt>
                            <dd class="break-words text-slate-700">
                                @if ($application->jobOpening)
                                    <a href="{{ route('admin.job-openings.edit', $application->jobOpening) }}" class="text-fuchsia-600 hover:underline">{{ $application->appliedFor() }}</a>
                                @else
                                    {{ $application->appliedFor() }}
                                @endif
                            </dd>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-admin.icon name="tag" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                        <div class="min-w-0">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Designation</dt>
                            <dd class="break-words text-slate-700">
                                {{ $application->designation_name ?: '—' }}
                                @if ($application->designation && $application->designation->name !== $application->designation_name)
                                    <span class="text-xs text-slate-400">(now “{{ $application->designation->name }}”)</span>
                                @endif
                                @if ($application->designation && ! $application->designation->is_active)
                                    <span class="ml-1 rounded-full bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500">Inactive</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </dl>

                <dl class="grid grid-cols-1 gap-4 pt-5 text-sm sm:grid-cols-2">
                    @foreach ($details as [$icon, $label, $value, $href])
                        <div class="flex items-start gap-2">
                            <x-admin.icon :name="$icon" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                            <div class="min-w-0">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</dt>
                                <dd class="break-words text-slate-700">
                                    @if ($value && $href)
                                        <a href="{{ $href }}" class="text-fuchsia-600 hover:underline" @if (str_starts_with($href, 'http')) target="_blank" rel="noopener noreferrer nofollow" @endif>{{ $value }}</a>
                                    @else
                                        {{ $value ?: '—' }}
                                    @endif
                                </dd>
                            </div>
                        </div>
                    @endforeach
                </dl>

                @if ($application->cover_letter)
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">Cover letter</span>
                        <p class="whitespace-pre-wrap rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $application->cover_letter }}</p>
                    </div>
                @endif

                <div class="mt-5 border-t border-slate-100 pt-4">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">Resume / CV</span>
                    @if ($application->hasResume())
                        <a href="{{ route('admin.job-applications.resume', $application) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm font-medium text-fuchsia-600 transition hover:bg-slate-100">
                            <x-admin.icon name="download" class="h-4 w-4" /> {{ $application->resume_name ?: $application->resumeDownloadName() }}
                            @if ($application->resume_size)
                                <span class="text-xs font-normal text-slate-400">{{ \Illuminate\Support\Number::fileSize($application->resume_size) }}</span>
                            @endif
                        </a>
                    @else
                        <p class="text-sm text-slate-400">No CV on file.</p>
                    @endif
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                    <a href="mailto:{{ $application->email }}?subject={{ rawurlencode('Your application'.($application->job_title ? ' for '.$application->job_title : '').' – '.$application->reference()) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                        <x-admin.icon name="envelope" class="h-4 w-4" /> Email candidate
                    </a>
                    @if ($canUpdate && ! $isArchived)
                        <form action="{{ route('admin.job-applications.archive', $application) }}" method="POST" onsubmit="return confirm('Archive this application?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                <x-admin.icon name="folder" class="h-4 w-4" /> Archive
                            </button>
                        </form>
                    @endif
                    @can('delete', $application)
                        <form action="{{ route('admin.job-applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Permanently delete this application and its CV? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                <x-admin.icon name="trash" class="h-4 w-4" /> Delete permanently
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            {{-- Internal notes --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="chat-alt" class="h-4 w-4 text-fuchsia-600" /> Internal notes
                </h3>

                @if ($canUpdate && ! $isArchived)
                    <form action="{{ route('admin.job-applications.notes.store', $application) }}" method="POST" class="mb-4">
                        @csrf
                        <label for="note-body" class="sr-only">Add a note</label>
                        <textarea id="note-body" name="body" rows="3" maxlength="5000" required placeholder="Add a private note — screening summary, interview feedback, next step…"
                            class="{{ $input }}">{{ old('body') }}</textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                                <x-admin.icon name="plus" class="h-4 w-4" /> Add note
                            </button>
                        </div>
                    </form>
                @endif

                <ul class="space-y-3">
                    @forelse ($application->notes as $note)
                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                            <p class="whitespace-pre-wrap text-sm text-slate-700">{{ $note->body }}</p>
                            <p class="mt-1.5 text-xs text-slate-400">
                                {{ $note->author?->name ?? 'Deleted user' }} · {{ $note->created_at->timezone($timezone)->format('M d, Y H:i') }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">No notes yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Pipeline controls + audit trail --}}
        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="clipboard-list" class="h-4 w-4 text-fuchsia-600" /> Pipeline
                </h3>
                @if ($canUpdate && ! $isArchived)
                    <form action="{{ route('admin.job-applications.update', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="application-status" class="mb-1 block text-xs font-medium text-slate-500">Status</label>
                            <select id="application-status" name="status" class="{{ $input }}">
                                @foreach (\App\Enums\JobApplicationStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $application->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="application-assignee" class="mb-1 block text-xs font-medium text-slate-500">Assigned to</label>
                            <select id="application-assignee" name="assigned_to" class="{{ $input }}">
                                <option value="">Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}" @selected((string) old('assigned_to', $application->assigned_to) === (string) $assignee->id)>{{ $assignee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                            <x-admin.icon name="check-circle" class="h-4 w-4" /> Save changes
                        </button>
                    </form>
                @else
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Status</dt>
                            <dd class="mt-0.5"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Assigned to</dt>
                            <dd class="mt-0.5 text-slate-700">{{ $application->assignee?->name ?? 'Unassigned' }}</dd>
                        </div>
                    </dl>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="clock" class="h-4 w-4 text-fuchsia-600" /> Activity
                </h3>
                <ol class="relative space-y-4 border-l border-slate-200 pl-4">
                    @forelse ($application->activities as $activity)
                        <li class="relative">
                            <span class="absolute -left-[1.3rem] top-1 h-2.5 w-2.5 rounded-full bg-fuchsia-400 ring-4 ring-white"></span>
                            <p class="text-sm text-slate-700">{{ $activity->description() }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $activity->user?->name ?? ($activity->isSystemEntry() ? 'Website' : 'System') }}
                                · {{ $activity->created_at->timezone($timezone)->format('M d, Y H:i') }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">No activity recorded yet.</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
@endsection
