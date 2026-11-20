@php
    $p = $product ?? null;
    $features = old('features') !== null
        ? (is_array(old('features')) ? implode("\n", old('features')) : (string) old('features'))
        : implode("\n", $p?->features ?? []);
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="product-name" class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
            <input id="product-name" type="text" name="name" value="{{ old('name', $p?->name) }}" required placeholder="TenderSet"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label for="product-icon" class="block text-sm font-medium text-slate-700 mb-1">Icon (Font Awesome class)</label>
            <input id="product-icon" type="text" name="icon" value="{{ old('icon', $p?->icon) }}" placeholder="fas fa-file-contract"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <p class="mt-1 text-xs text-slate-400">e.g. <code>fas fa-qrcode</code>. See fontawesome.com/icons.</p>
        </div>
    </div>

    <div>
        <label for="product-description" class="block text-sm font-medium text-slate-700 mb-1">Description *</label>
        <textarea id="product-description" name="description" rows="3" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $p?->description) }}</textarea>
    </div>

    <div>
        <label for="product-features" class="block text-sm font-medium text-slate-700 mb-1">Key features (one per line, up to 6)</label>
        <textarea id="product-features" name="features" rows="4" placeholder="Tenders from GeM, CPPP and state portals&#10;Email and WhatsApp alerts"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ $features }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="product-url" class="block text-sm font-medium text-slate-700 mb-1">Website</label>
            <input id="product-url" type="url" name="url" value="{{ old('url', $p?->url) }}" placeholder="https://tenderset.in"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label for="product-case-study" class="block text-sm font-medium text-slate-700 mb-1">Case study</label>
            <select id="product-case-study" name="case_study_id"
                class="w-full rounded-lg border border-slate-300 bg-white px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">None</option>
                @foreach ($caseStudies as $id => $title)
                    <option value="{{ $id }}" @selected((int) old('case_study_id', $p?->case_study_id) === $id)>{{ $title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="product-sort-order" class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
        <input id="product-sort-order" type="number" name="sort_order" value="{{ old('sort_order', $p?->sort_order ?? 0) }}" min="0"
            class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
