@php $j = $jobOpening ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Job Title *</label>
        <input type="text" name="title" value="{{ old('title', $j?->title) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
            <input type="text" name="department" value="{{ old('department', $j?->department) }}" placeholder="e.g. Development"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (Font Awesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $j?->icon) }}" placeholder="fas fa-code"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location', $j?->location ?? 'Navi Mumbai, India') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Employment Type *</label>
            <select name="employment_type" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach (\App\Models\JobOpening::EMPLOYMENT_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('employment_type', $j?->employment_type ?? 'Full-time') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $j?->sort_order ?? 0) }}" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex items-center gap-2 pt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $j?->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</div>
