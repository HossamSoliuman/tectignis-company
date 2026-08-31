@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Pre-bid Queries" icon="question-mark-circle" :count="$clarifications->count()">
                <div class="divide-y divide-slate-100">
                    @forelse ($clarifications as $clarification)
                        <div class="px-4 py-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="whitespace-pre-line text-sm font-medium text-slate-800">{{ $clarification->question }}</p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $clarification->raisedBy?->name ?? 'Unknown' }}
                                        @if ($clarification->raised_on) · raised {{ $clarification->raised_on->format('d M Y') }} @endif
                                        @if ($clarification->submitted_through) · via {{ $clarification->submitted_through }} @endif
                                    </p>
                                </div>
                                <x-portal.badge :color="$clarification->status->badgeColor()" :label="$clarification->status->label()" />
                            </div>

                            @if ($clarification->response)
                                <div class="mt-3 rounded-lg border border-slate-100 bg-slate-50 p-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Authority's answer</p>
                                    <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $clarification->response }}</p>
                                    @if ($clarification->response_on)
                                        <p class="mt-1 text-xs text-slate-400">{{ $clarification->response_on->format('d M Y') }}</p>
                                    @endif
                                </div>
                            @endif

                            @if ($clarification->attachment)
                                <a href="{{ route('admin.portal.files.show', $clarification->attachment) }}"
                                    class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-fuchsia-600 hover:underline">
                                    <x-admin.icon name="paper-clip" class="h-3.5 w-3.5" /> {{ $clarification->attachment->original_name }}
                                </a>
                            @endif

                            @can('update', $tender)
                                <div class="mt-3 flex flex-wrap items-end gap-2">
                                    <form action="{{ route('admin.portal.tenders.clarifications.update', [$tender, $clarification]) }}" method="POST"
                                        enctype="multipart/form-data" class="flex flex-1 flex-wrap items-end gap-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="min-w-48 flex-1">
                                            <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Answer</label>
                                            <textarea name="response" rows="2"
                                                class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ $clarification->response }}</textarea>
                                        </div>
                                        <div class="min-w-32">
                                            <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Status</label>
                                            <select name="status" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                                @foreach ($clarificationStatuses as $value => $label)
                                                    <option value="{{ $value }}" @selected($clarification->status->value === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="min-w-36">
                                            <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Reply document</label>
                                            <input type="file" name="attachment"
                                                class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs file:font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                        </div>
                                        <button type="submit" class="rounded-lg bg-slate-800 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-slate-900">Save</button>
                                    </form>

                                    <x-admin.delete-button :action="route('admin.portal.tenders.clarifications.destroy', [$tender, $clarification])"
                                        confirm="Remove this clarification?" />
                                </div>
                            @endcan
                        </div>
                    @empty
                        <div><x-portal.empty-state message="No queries raised on this tender." icon="question-mark-circle" /></div>
                    @endforelse
                </div>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            @can('update', $tender)
                <x-portal.panel title="Raise a Query" icon="plus">
                    <form action="{{ route('admin.portal.tenders.clarifications.store', $tender) }}" method="POST" class="space-y-3 p-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Question *</label>
                            <textarea name="question" rows="4" required placeholder="Exactly as it will be submitted…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400"></textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Submitted through</label>
                            <input type="text" name="submitted_through" placeholder="GeM query, email, pre-bid meeting…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Raised on</label>
                            <input type="date" name="raised_on" value="{{ now()->toDateString() }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Record Query</button>
                    </form>
                </x-portal.panel>

                <x-portal.panel title="Still Open" icon="clock">
                    <p class="px-4 py-3 text-sm text-slate-600">
                        {{ $summary['clarifications']['pending'] }} of {{ $summary['clarifications']['total'] }} queries are
                        unanswered. An unanswered query close to the deadline is a decision somebody has to make, not a
                        detail to leave open.
                    </p>
                </x-portal.panel>
            @endcan
        </div>
    </div>
@endsection
