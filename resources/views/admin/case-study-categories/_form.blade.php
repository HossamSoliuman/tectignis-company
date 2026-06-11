@php $c = $caseStudyCategory ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Category Name *</label>
        <input type="text" name="name" value="{{ old('name', $c?->name) }}" required placeholder="Enter category name"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description (optional)</label>
        <textarea name="description" rows="3" placeholder="Enter category description"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $c?->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $c?->sort_order ?? 0) }}" min="0"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <p class="mt-1 text-xs text-slate-400">Categories with lower numbers appear first.</p>
    </div>
    <div>
        <p class="block text-sm font-medium text-slate-700 mb-1">Status</p>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $c?->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</div>
