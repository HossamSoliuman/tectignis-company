@php
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Designation Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name', $designation->name) }}" required maxlength="255" placeholder="e.g. Senior Software Engineer" class="{{ $input }}">
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="department" class="mb-1 block text-sm font-medium text-slate-700">Department</label>
            <input type="text" id="department" name="department" value="{{ old('department', $designation->department) }}" maxlength="255" placeholder="e.g. Development" class="{{ $input }}">
        </div>
        <div>
            <label for="sort_order" class="mb-1 block text-sm font-medium text-slate-700">Display Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $designation->sort_order) }}" min="0" max="65535" class="{{ $input }}">
        </div>
    </div>
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $designation->is_active ?? true))>
        Active — shown in the careers form dropdown
    </label>

    @if ($designation->exists)
        <dl class="grid grid-cols-1 gap-3 border-t border-slate-100 pt-4 text-xs text-slate-500 sm:grid-cols-3">
            <div>
                <dt class="font-semibold uppercase tracking-wide text-slate-400">Created</dt>
                <dd>{{ $designation->created_at?->timezone(config('app.timezone'))->format('M d, Y H:i') }}{{ $designation->creator ? ' · '.$designation->creator->name : '' }}</dd>
            </div>
            <div>
                <dt class="font-semibold uppercase tracking-wide text-slate-400">Updated</dt>
                <dd>{{ $designation->updated_at?->timezone(config('app.timezone'))->format('M d, Y H:i') }}{{ $designation->updater ? ' · '.$designation->updater->name : '' }}</dd>
            </div>
            <div>
                <dt class="font-semibold uppercase tracking-wide text-slate-400">Applications</dt>
                <dd>{{ number_format($designation->applications_count ?? 0) }}</dd>
            </div>
        </dl>
    @endif
</div>
