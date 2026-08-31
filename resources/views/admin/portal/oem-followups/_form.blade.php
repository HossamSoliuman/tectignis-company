@php $f = $followup ?? null; @endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">OEM *</label>
            <input type="text" name="oem_name" value="{{ old('oem_name', $f?->oem_name) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">What we need *</label>
            <select name="requirement_type" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($requirementTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('requirement_type', $f?->requirement_type?->value ?? 'maf') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @unless ($f)
        {{-- Only settable at creation: moving a chase between tenders would
             detach its history from the bid it was raised for. --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">For tender</label>
            <select name="tender_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Not tied to a tender</option>
                @foreach ($tenders as $tender)
                    <option value="{{ $tender->id }}" @selected((string) old('tender_id') === (string) $tender->id)>{{ $tender->code }} — {{ $tender->title }}</option>
                @endforeach
            </select>
        </div>
    @endunless

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Product</label>
        <input type="text" name="product" value="{{ old('product', $f?->product) }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Contact person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $f?->contact_person) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Email / phone</label>
            <input type="text" name="contact_channel" value="{{ old('contact_channel', $f?->contact_channel) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Requested on</label>
            <input type="date" name="requested_on" value="{{ old('requested_on', $f?->requested_on?->format('Y-m-d') ?? now()->toDateString()) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Needed by</label>
            <input type="date" name="required_by" value="{{ old('required_by', $f?->required_by?->format('Y-m-d')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Next chase</label>
            <input type="date" name="next_followup_at" value="{{ old('next_followup_at', $f?->next_followup_at?->format('Y-m-d')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
        <select name="status" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            @foreach ($followupStatuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $f?->status?->value ?? 'not_requested') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Remarks</label>
        <textarea name="remarks" rows="3"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('remarks', $f?->remarks) }}</textarea>
    </div>
</div>
