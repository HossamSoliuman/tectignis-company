@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Eligibility Checklist" icon="scale" :count="$counts['total']">
                <div class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <form action="{{ route('admin.portal.tenders.eligibility.update', [$tender, $item]) }}" method="POST"
                            class="grid grid-cols-1 gap-3 px-4 py-3 sm:grid-cols-12 sm:items-center">
                            @csrf
                            @method('PUT')
                            <div class="sm:col-span-5">
                                <p class="text-sm font-medium text-slate-800">{{ $item->requirement }}</p>
                                @if ($item->category)
                                    <p class="text-xs text-slate-400">{{ Str::headline($item->category) }}</p>
                                @endif
                            </div>
                            <div class="sm:col-span-2">
                                <select name="status" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @foreach ($itemStatuses as $value => $label)
                                        <option value="{{ $value }}" @selected($item->status->value === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <select name="responsible_employee_id" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    <option value="">Nobody</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" @selected($item->responsible_employee_id === $employee->id)>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <input type="text" name="remarks" value="{{ $item->remarks }}" placeholder="Remarks"
                                    class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div class="flex items-center justify-end gap-1 sm:col-span-1">
                                @can('update', $tender)
                                    <button type="submit" title="Save" class="rounded-lg border border-slate-200 p-1.5 text-slate-500 transition hover:bg-slate-50 hover:text-fuchsia-600">
                                        <x-admin.icon name="check-circle" class="h-4 w-4" />
                                    </button>
                                @endcan
                            </div>
                        </form>
                    @empty
                        <div><x-portal.empty-state message="No eligibility requirements listed." icon="scale" /></div>
                    @endforelse
                </div>
            </x-portal.panel>

            @can('update', $tender)
                <x-portal.panel title="Add a Requirement" icon="plus">
                    <form action="{{ route('admin.portal.tenders.eligibility.store', $tender) }}" method="POST" class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-12">
                        @csrf
                        <div class="sm:col-span-5">
                            <input type="text" name="requirement" required placeholder="Requirement *"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div class="sm:col-span-3">
                            <input type="text" name="category" placeholder="Category"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        </div>
                        <div class="sm:col-span-3">
                            <select name="responsible_employee_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                <option value="">Nobody</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-1">
                            <button type="submit" class="w-full rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Add</button>
                        </div>
                    </form>
                </x-portal.panel>
            @endcan
        </div>

        <div class="space-y-5">
            <x-portal.panel title="Verdict" icon="shield-check">
                <div class="space-y-4 px-4 py-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Current</p>
                        <p class="mt-1"><x-portal.badge :color="$tender->eligibility_status->badgeColor()" :label="$tender->eligibility_status->label()" /></p>
                    </div>

                    @if ($isOverridden)
                        {{-- An override outranks the checklist, so the checklist's own
                             answer is shown next to it rather than hidden. --}}
                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Overridden by management</p>
                            <p class="mt-1 text-sm text-amber-800">{{ $tender->eligibility_override_reason }}</p>
                            <p class="mt-2 text-xs text-amber-600">The checklist itself says: {{ $derived->label() }}.</p>
                        </div>
                    @endif

                    <dl class="space-y-1.5 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-400">Met</dt><dd class="font-medium text-emerald-600">{{ $counts['met'] }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Not met</dt><dd class="font-medium text-rose-600">{{ $counts['not_met'] }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Pending</dt><dd class="font-medium text-amber-600">{{ $counts['pending'] }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Not applicable</dt><dd class="text-slate-500">{{ $counts['not_applicable'] }}</dd></div>
                    </dl>
                </div>
            </x-portal.panel>

            @can('overrideEligibility', $tender)
                <x-portal.panel title="Override the Verdict" icon="lock-closed">
                    <form action="{{ route('admin.portal.tenders.eligibility.override', $tender) }}" method="POST" class="space-y-3 p-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Set eligibility to</label>
                            <select name="eligibility_status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                @foreach ($eligibilityStatuses as $value => $label)
                                    <option value="{{ $value }}" @selected($tender->eligibility_status->value === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Why? *</label>
                            <textarea name="eligibility_override_reason" rows="3" required minlength="10"
                                placeholder="This goes on the permanent record."
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('eligibility_override_reason', $tender->eligibility_override_reason) }}</textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Override</button>
                        </div>
                    </form>

                    @if ($isOverridden)
                        <form action="{{ route('admin.portal.tenders.eligibility.override.clear', $tender) }}" method="POST" class="border-t border-slate-100 px-4 py-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-slate-500 transition hover:text-rose-600">Clear the override</button>
                        </form>
                    @endif
                </x-portal.panel>
            @endcan
        </div>
    </div>
@endsection
