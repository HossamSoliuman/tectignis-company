@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Final Pre-submission Checklist" icon="check-circle" :count="$checks->count()">
                <div class="divide-y divide-slate-100">
                    @forelse ($checks as $check)
                        <form action="{{ route('admin.portal.tenders.submission.update', [$tender, $check]) }}" method="POST"
                            class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_checked" value="{{ $check->is_checked ? 0 : 1 }}">
                            <label class="flex min-w-0 items-center gap-3">
                                <input type="checkbox" onchange="this.form.submit()" @checked($check->is_checked)
                                    @disabled(! auth()->user()->can('update', $tender))
                                    class="h-4 w-4 rounded border-slate-300 text-fuchsia-600 focus:ring-fuchsia-400">
                                <span class="text-sm {{ $check->is_checked ? 'text-slate-400 line-through' : 'font-medium text-slate-800' }}">{{ $check->label }}</span>
                            </label>
                            @if ($check->is_checked)
                                <span class="shrink-0 text-xs text-slate-400">
                                    {{ $check->checkedBy?->name ?? 'Someone' }} · {{ $check->checked_at?->format('d M Y, g:i A') }}
                                </span>
                            @endif
                        </form>
                    @empty
                        <div><x-portal.empty-state message="No submission checklist on this tender." icon="check-circle" /></div>
                    @endforelse
                </div>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            <x-portal.panel title="Clearance" icon="shield-check">
                <div class="space-y-4 px-4 py-4">
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-400">Checks remaining</dt>
                            <dd class="font-medium {{ $remaining > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $remaining }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-400">Mandatory docs missing</dt>
                            <dd class="font-medium {{ $missingMandatory > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $missingMandatory }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-400">Deadline</dt>
                            <dd class="text-slate-700">{{ $tender->submission_deadline_at?->format('d M Y, g:i A') ?? 'Not set' }}</dd>
                        </div>
                    </dl>

                    @if ($tender->stage->isSubmittedOrLater())
                        <p class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
                            This tender is already marked {{ $tender->stage->label() }}.
                        </p>
                    @elseif ($canSubmit)
                        @can('update', $tender)
                            {{-- Only reachable when every line is ticked and no mandatory
                                 document is outstanding — the controller re-checks anyway. --}}
                            <form action="{{ route('admin.portal.tenders.submission.mark', $tender) }}" method="POST"
                                onsubmit="return confirm('Mark this tender as submitted?')">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                                    Mark as Submitted
                                </button>
                            </form>
                        @endcan
                    @else
                        <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                            Not clear to submit yet — finish the checklist and the mandatory documents first.
                        </p>
                    @endif
                </div>
            </x-portal.panel>

            <x-portal.panel title="Why This List Exists" icon="light-bulb">
                <p class="px-4 py-3 text-sm text-slate-600">
                    Most lost bids are lost on process, not price: the wrong envelope, a missing signature, an upload
                    started ten minutes before close. Every line ticked here records who checked it and when.
                </p>
            </x-portal.panel>
        </div>
    </div>
@endsection
