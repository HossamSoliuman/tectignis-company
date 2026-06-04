@php $r = $redirect ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">From Path * (e.g. /old-page)</label>
        <input type="text" name="from_path" value="{{ old('from_path', $r?->from_path) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">To Path * (e.g. /new-page or full URL)</label>
        <input type="text" name="to_path" value="{{ old('to_path', $r?->to_path) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Status Code</label>
        <select name="status_code"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <option value="301" {{ old('status_code', $r?->status_code) == 301 ? 'selected' : '' }}>301 — Permanent</option>
            <option value="302" {{ old('status_code', $r?->status_code) == 302 ? 'selected' : '' }}>302 — Temporary</option>
        </select>
    </div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $r?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
