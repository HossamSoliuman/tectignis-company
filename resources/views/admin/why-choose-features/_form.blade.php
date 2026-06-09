@php $f = $feature ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $f?->title) }}" required placeholder="Global Delivery Model"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (Font Awesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $f?->icon) }}" placeholder="fas fa-globe"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <p class="mt-1 text-xs text-slate-400">e.g. <code>fas fa-globe</code>. See fontawesome.com/icons.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Text *</label>
        <textarea name="text" rows="2" required placeholder="We leverage a global talent pool to deliver round-the-clock progress."
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('text', $f?->text) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $f?->sort_order ?? 0) }}" min="0"
            class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $f?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
