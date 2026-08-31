@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Outcome" icon="chart-bar">
                @if ($result)
                    <dl class="grid grid-cols-1 gap-4 px-4 py-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Result</dt>
                            <dd class="mt-1"><x-portal.badge :color="$result->outcome->badgeColor()" :label="$result->outcome->label()" /></dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Awarded Value</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ $result->awarded_value ? '₹'.number_format((float) $result->awarded_value) : '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Technical</dt>
                            <dd class="mt-0.5 text-slate-700">{{ $result->technical_result ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Financial</dt>
                            <dd class="mt-0.5 text-slate-700">{{ $result->financial_result ?? '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Remarks</dt>
                            <dd class="mt-0.5 whitespace-pre-line text-slate-700">{{ $result->remarks ?? '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Recorded By</dt>
                            <dd class="mt-0.5 text-slate-700">
                                {{ $result->recordedBy?->name ?? 'Unknown' }} · {{ $result->updated_at?->format('d M Y, g:i A') }}
                            </dd>
                        </div>
                    </dl>

                    @if ($result->attachments->isNotEmpty())
                        <ul class="divide-y divide-slate-100 border-t border-slate-100">
                            @foreach ($result->attachments as $attachment)
                                <li class="flex items-center justify-between gap-3 px-4 py-3">
                                    <a href="{{ route('admin.portal.files.show', $attachment) }}" class="min-w-0 truncate text-sm font-medium text-slate-700 hover:text-fuchsia-700">
                                        {{ $attachment->original_name }}
                                    </a>
                                    <span class="shrink-0 text-xs text-slate-400">{{ $attachment->humanSize() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <x-portal.empty-state message="No result recorded for this tender yet." icon="chart-bar" />
                @endif
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            @if ($canRecord)
                <x-portal.panel :title="$result ? 'Correct the Result' : 'Record the Result'" icon="pencil">
                    <form action="{{ route('admin.portal.tenders.result.store', $tender) }}" method="POST" enctype="multipart/form-data" class="space-y-3 p-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Outcome *</label>
                            <select name="outcome" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                @foreach ($outcomes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('outcome', $result?->outcome?->value) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Awarded Value (₹)</label>
                            <input type="number" step="0.01" min="0" name="awarded_value" value="{{ old('awarded_value', $result?->awarded_value) }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            <p class="mt-1 text-xs text-slate-400">Required when the outcome is Won.</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Technical Result</label>
                            <input type="text" name="technical_result" value="{{ old('technical_result', $result?->technical_result) }}" placeholder="Qualified / disqualified…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Financial Result</label>
                            <input type="text" name="financial_result" value="{{ old('financial_result', $result?->financial_result) }}" placeholder="L1 / L2 / L3…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Remarks</label>
                            <textarea name="remarks" rows="3" placeholder="What decided it? This is the note that improves the next bid."
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('remarks', $result?->remarks) }}</textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Award / result letter</label>
                            <input type="file" name="attachment"
                                class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-sm file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs file:font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">
                            {{ $result ? 'Update Result' : 'Record Result' }}
                        </button>
                    </form>
                </x-portal.panel>
            @else
                <x-portal.panel title="Recording the Result" icon="lock-closed">
                    <p class="px-4 py-3 text-sm text-slate-600">
                        Won or lost closes the commercial loop and feeds the win-rate reports, so it is recorded by
                        management rather than by whoever prepared the bid.
                    </p>
                </x-portal.panel>
            @endif
        </div>
    </div>
@endsection
