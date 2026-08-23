@php $d = $department ?? null; @endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Name *</label>
            <input type="text" name="name" value="{{ old('name', $d?->name) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Code *</label>
            <input type="text" name="code" value="{{ old('code', $d?->code) }}" required placeholder="e.g. TEND"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Department Head</label>
            <select name="head_employee_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Not set</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) old('head_employee_id', $d?->head_employee_id) === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Sort Order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $d?->sort_order ?? 0) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $d?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
