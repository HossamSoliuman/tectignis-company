@php $s = $step ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $s?->title) }}" required placeholder="Understand"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description *</label>
        <textarea name="description" rows="2" required placeholder="We understand your business challenges"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $s?->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Icon SVG Path</label>
        <textarea name="icon" rows="3" placeholder="<path d=&quot;…&quot;/>"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('icon', $s?->icon) }}</textarea>
        <p class="mt-1 text-xs text-slate-400">Inner SVG markup (e.g. a Heroicon <code>&lt;path .../&gt;</code>), rendered inside a 24×24 viewBox.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $s?->sort_order ?? 0) }}" min="0"
            class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $s?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
