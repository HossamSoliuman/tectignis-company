@php $s = $page ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $s?->title) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $s?->slug) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <p class="mt-1 text-xs text-slate-400">The page will be available at <code>/{slug}</code>.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Page HTML (body) *</label>
        <p class="mb-1 text-xs text-slate-400">The full HTML of the page, rendered inside the public site layout.</p>
        <textarea name="body" rows="20" required
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
