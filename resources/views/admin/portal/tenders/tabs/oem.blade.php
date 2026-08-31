@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="OEM Follow-ups" icon="share" :count="$followups->count()"
                :action="route('admin.portal.oem-followups.index')" action-label="Full chase queue">
                <div class="divide-y divide-slate-100">
                    @forelse ($followups as $followup)
                        <div class="px-4 py-3 {{ $followup->isOverdue() ? 'bg-rose-50/40' : '' }}">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.portal.oem-followups.show', $followup) }}"
                                        class="text-sm font-medium text-slate-800 hover:text-fuchsia-700">{{ $followup->oem_name }}</a>
                                    <p class="text-xs text-slate-400">
                                        {{ $followup->requirement_type->label() }}
                                        @if ($followup->product) · {{ $followup->product }} @endif
                                        @if ($followup->contact_person) · {{ $followup->contact_person }} @endif
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <x-portal.badge :color="$followup->status->badgeColor()" :label="$followup->status->label()" />
                                </div>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-slate-500">
                                <span>Needed by: <span class="{{ $followup->isOverdue() ? 'font-semibold text-rose-600' : '' }}">{{ $followup->required_by?->format('d M Y') ?? '—' }}</span></span>
                                <span>Next chase: <span class="{{ $followup->isChaseDue() ? 'font-semibold text-amber-600' : '' }}">{{ $followup->next_followup_at?->format('d M Y') ?? '—' }}</span></span>
                                <span>{{ $followup->followupUpdates->count() }} contact(s) logged</span>
                            </div>

                            @if ($followup->followupUpdates->isNotEmpty())
                                <p class="mt-2 truncate text-xs italic text-slate-400">
                                    Last: {{ $followup->followupUpdates->first()->note }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div><x-portal.empty-state message="Nothing is being chased from any OEM for this tender." icon="share" /></div>
                    @endforelse
                </div>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            @can('update', $tender)
                <x-portal.panel title="Raise a Follow-up" icon="plus">
                    <form action="{{ route('admin.portal.tenders.oem.store', $tender) }}" method="POST" class="space-y-3 p-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">OEM *</label>
                            <input type="text" name="oem_name" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">What we need *</label>
                            <select name="requirement_type" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                @foreach ($requirementTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Product</label>
                            <input type="text" name="product"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Needed by</label>
                                <input type="date" name="required_by"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Next chase</label>
                                <input type="date" name="next_followup_at"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Contact</label>
                                <input type="text" name="contact_person"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
                                <select name="status" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @foreach ($followupStatuses as $value => $label)
                                        <option value="{{ $value }}" @selected($value === 'requested')>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Raise Follow-up</button>
                    </form>
                </x-portal.panel>
            @endcan

            <x-portal.panel title="Why This Matters" icon="light-bulb">
                <p class="px-4 py-3 text-sm text-slate-600">
                    An MAF that never arrives disqualifies an otherwise winning bid. Every chase logged here is
                    permanent — if a bid has to be abandoned, the record shows exactly how often the OEM was asked.
                </p>
            </x-portal.panel>
        </div>
    </div>
@endsection
