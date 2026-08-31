{{--
    One line of the document checklist. Rendered by the Documents tab and by the
    Technical / Commercial preparation tabs, so a document is edited the same way
    wherever it is seen. Verify and delete are sibling forms, never nested.
--}}
<div class="px-4 py-3">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="flex items-center gap-2 text-sm font-medium text-slate-800">
                {{ $document->name }}
                @if ($document->is_required)
                    <span class="text-xs font-semibold text-rose-500">*</span>
                @endif
            </p>
            <p class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                <x-portal.badge :color="$document->category->badgeColor()" :label="$document->category->label()" :dot="false" />
                @if ($document->attachment)
                    <a href="{{ route('admin.portal.files.show', $document->attachment) }}"
                        class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                        <x-admin.icon name="paper-clip" class="h-3.5 w-3.5" /> {{ $document->attachment->original_name }} (v{{ $document->version }})
                    </a>
                @else
                    <span>No file uploaded</span>
                @endif
                @if ($document->isExpired())
                    <span class="font-medium text-rose-600">Expired {{ $document->expires_on->format('d M Y') }}</span>
                @endif
            </p>
        </div>

        <div class="flex shrink-0 items-center gap-2">
            <x-portal.badge :color="$document->status->badgeColor()" :label="$document->status->label()" />
            @if ($document->isVerified())
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                    <x-admin.icon name="shield-check" class="h-3.5 w-3.5" />
                    {{ $document->verifier?->name ?? 'Verified' }}
                </span>
            @endif
        </div>
    </div>

    @can('update', $tender)
        <div class="mt-3 flex flex-wrap items-end gap-2">
            <form action="{{ route('admin.portal.tenders.documents.update', [$tender, $document]) }}" method="POST"
                enctype="multipart/form-data" class="flex flex-1 flex-wrap items-end gap-2">
                @csrf
                @method('PUT')
                <div class="min-w-32 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Status</label>
                    <select name="status" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        @foreach ($documentStatuses as $value => $label)
                            <option value="{{ $value }}" @selected($document->status->value === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-32 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Responsible</label>
                    <select name="responsible_employee_id" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        <option value="">Nobody</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected($document->responsible_employee_id === $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-36 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Needed by</label>
                    <input type="datetime-local" name="required_by" value="{{ $document->required_by?->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div class="min-w-32 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Valid until</label>
                    <input type="date" name="expires_on" value="{{ $document->expires_on?->format('Y-m-d') }}"
                        class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div class="min-w-40 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Upload / replace</label>
                    <input type="file" name="attachment"
                        class="w-full rounded-lg border border-slate-300 px-2 py-1 text-xs file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs file:font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div class="min-w-40 flex-1">
                    <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Remarks</label>
                    <input type="text" name="remarks" value="{{ $document->remarks }}"
                        class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <button type="submit" class="rounded-lg bg-slate-800 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-slate-900">Save</button>
            </form>

            @can('verifyDocuments', $tender)
                @unless ($document->isVerified())
                    <form action="{{ route('admin.portal.tenders.documents.verify', [$tender, $document]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">
                            Verify
                        </button>
                    </form>
                @endunless
            @endcan

            <x-admin.delete-button :action="route('admin.portal.tenders.documents.destroy', [$tender, $document])"
                confirm="Remove this document requirement?" />
        </div>
    @endcan
</div>
