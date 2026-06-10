@php $c = $faqCategory ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
        <input type="text" name="name" value="{{ old('name', $c?->name) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (Font Awesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $c?->icon) }}" placeholder="fas fa-cloud"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <p class="mt-1 text-xs text-slate-400">Shown next to the category on the public FAQs page.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $c?->sort_order ?? 0) }}" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>
</div>
