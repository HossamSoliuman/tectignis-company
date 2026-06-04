@php $p = $post ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $p?->title) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug (auto-generated if empty)</label>
            <input type="text" name="slug" value="{{ old('slug', $p?->slug) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Published At</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', $p?->published_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $p?->sort_order ?? 0) }}" min="0"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <p class="mt-1 text-xs text-slate-400">Lower numbers appear first; ties fall back to newest published date.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="2"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('excerpt', $p?->excerpt) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Content * (HTML)</label>
        <textarea name="content" rows="20" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('content', $p?->content) }}</textarea>
    </div>

    <x-admin.image-field name="image" label="Featured Image" :current="$p?->image" />

    <div class="border-t border-slate-100 pt-4 space-y-4">
        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">SEO</h3>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">SEO Title</label>
            <input type="text" name="seo_title" value="{{ old('seo_title', $p?->seo_title) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">SEO Description</label>
            <textarea name="seo_description" rows="2"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('seo_description', $p?->seo_description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">SEO Keywords</label>
            <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $p?->seo_keywords) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" id="is_published" name="is_published" value="1"
            {{ old('is_published', $p?->is_published) ? 'checked' : '' }}
            class="rounded border-slate-300">
        <label for="is_published" class="text-sm font-medium text-slate-700">Published</label>
    </div>
</div>
