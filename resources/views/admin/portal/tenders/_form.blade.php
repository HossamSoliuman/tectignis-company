@php $t = $tender ?? null; @endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">The Opportunity</h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Tender Number *</label>
            <input type="text" name="tender_number" value="{{ old('tender_number', $t?->tender_number) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Portal *</label>
            <select name="portal" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($sources as $value => $label)
                    <option value="{{ $value }}" @selected(old('portal', $t?->portal?->value ?? 'gem') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Title *</label>
        <input type="text" name="title" value="{{ old('title', $t?->title) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Customer / Department</label>
            <input type="text" name="customer_organization" value="{{ old('customer_organization', $t?->customer_organization) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Tender URL</label>
            <input type="url" name="tender_url" value="{{ old('tender_url', $t?->tender_url) }}" placeholder="https://…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>
</div>

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    {{-- Date *and* time everywhere: a tender portal closes at an hour, not a day. --}}
    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Key Dates</h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Published</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $t?->published_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Pre-bid Meeting</label>
            <input type="datetime-local" name="pre_bid_at" value="{{ old('pre_bid_at', $t?->pre_bid_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Submission Opens</label>
            <input type="datetime-local" name="submission_start_at" value="{{ old('submission_start_at', $t?->submission_start_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Submission Deadline</label>
            <input type="datetime-local" name="submission_deadline_at" value="{{ old('submission_deadline_at', $t?->submission_deadline_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <p class="mt-1 text-xs text-slate-400">Everything in the workspace counts down to this.</p>
        </div>
    </div>
</div>

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Money</h3>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Estimated Value (₹)</label>
        <input type="number" step="0.01" min="0" name="estimated_value" value="{{ old('estimated_value', $t?->estimated_value) }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="emd_required" value="0">
                <input type="checkbox" name="emd_required" value="1" @checked(old('emd_required', $t?->emd_required)) class="rounded border-slate-300">
                EMD required
            </label>
            <input type="number" step="0.01" min="0" name="emd_amount" value="{{ old('emd_amount', $t?->emd_amount) }}" placeholder="EMD amount"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="fee_required" value="0">
                <input type="checkbox" name="fee_required" value="1" @checked(old('fee_required', $t?->fee_required)) class="rounded border-slate-300">
                Tender fee required
            </label>
            <input type="number" step="0.01" min="0" name="fee_amount" value="{{ old('fee_amount', $t?->fee_amount) }}" placeholder="Fee amount"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>
</div>

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Ownership &amp; Status</h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Bid Owner</label>
            <select name="assigned_employee_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Unassigned</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) old('assigned_employee_id', $t?->assigned_employee_id) === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Technical Owner</label>
            <select name="technical_owner_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Unassigned</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) old('technical_owner_id', $t?->technical_owner_id) === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Commercial Owner</label>
            <select name="sales_owner_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Unassigned</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) old('sales_owner_id', $t?->sales_owner_id) === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Stage *</label>
            <select name="stage" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($stages as $value => $label)
                    <option value="{{ $value }}" @selected(old('stage', $t?->stage?->value ?? 'identified') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Bid / No-bid *</label>
            <select name="decision" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($decisions as $value => $label)
                    <option value="{{ $value }}" @selected(old('decision', $t?->decision?->value ?? 'under_review') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
        <textarea name="notes" rows="4"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('notes', $t?->notes) }}</textarea>
    </div>
</div>
