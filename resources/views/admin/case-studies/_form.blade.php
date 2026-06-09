@php $cs = $caseStudy ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $cs?->title) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $cs?->category) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $cs?->slug) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $cs?->sort_order ?? 0) }}" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Short Description</label>
        <textarea name="short_description" rows="2"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('short_description', $cs?->short_description) }}</textarea>
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 space-y-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Home Page Card</p>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Theme</label>
                <select name="theme"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    @foreach (['blue' => 'Blue', 'red' => 'Red', 'purple' => 'Purple'] as $value => $label)
                        <option value="{{ $value }}" {{ old('theme', $cs?->theme) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Icon SVG Path</label>
                <input type="text" name="icon" value="{{ old('icon', $cs?->icon) }}" placeholder="M9 17.25v1.007a3 3 0 0 1…"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Features (one per line)</label>
            <textarea name="features" rows="3" placeholder="40% increase in operational efficiency&#10;Real-time data visibility"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('features') ? implode("\n", (array) old('features')) : implode("\n", $cs?->features ?? []) }}</textarea>
            <p class="mt-1 text-xs text-slate-400">Shown as a checklist on the home page case-study card.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Full Content (HTML)</label>
        <textarea name="content" rows="10"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('content', $cs?->content) }}</textarea>
    </div>

    <x-admin.image-field name="image" label="Image" :current="$cs?->image" />

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $cs?->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
