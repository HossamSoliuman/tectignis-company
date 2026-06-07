@php $s = $industry ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $s?->name) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $s?->slug) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (FontAwesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $s?->icon) }}" placeholder="fas fa-industry"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Short Description</label>
        <textarea name="short_description" rows="2"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('short_description', $s?->short_description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Full Description (HTML)</label>
        <textarea name="description" rows="6"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $s?->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Custom Page HTML (body)</label>
        <p class="mb-1 text-xs text-slate-400">When filled, this raw HTML replaces the default detail-page layout. Leave empty to use the standard template.</p>
        <textarea name="body" rows="14"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('body', $s?->body) }}</textarea>
    </div>

    <div class="border-t border-slate-100 pt-4 space-y-4">
        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">SEO</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $s?->seo_title) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">SEO Keywords</label>
                <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $s?->seo_keywords) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">SEO Description</label>
            <textarea name="seo_description" rows="2"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('seo_description', $s?->seo_description) }}</textarea>
        </div>
    </div>

</div>
