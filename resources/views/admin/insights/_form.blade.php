@php $i = $insight ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $i?->title) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug (auto-generated if empty)</label>
            <input type="text" name="slug" value="{{ old('slug', $i?->slug) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Published At</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', $i?->published_at?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Topic</label>
            <input type="text" name="topic" value="{{ old('topic', $i?->topic) }}" list="insight-topics"
                placeholder="e.g. Cloud Computing"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <datalist id="insight-topics">
                @foreach ($topics ?? [] as $topic)
                    <option value="{{ $topic }}"></option>
                @endforeach
            </datalist>
            <p class="mt-1 text-xs text-slate-400">Used for the filter pills and popular topics on the public page.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Author</label>
            <input type="text" name="author" value="{{ old('author', $i?->author ?? 'Tectignis Team') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="2"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('excerpt', $i?->excerpt) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Content * (HTML)</label>
        <textarea name="content" rows="20" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('content', $i?->content) }}</textarea>
    </div>

    <div class="border-t border-slate-100 pt-4 space-y-4">
        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">SEO</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $i?->seo_title) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">SEO Keywords</label>
                <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $i?->seo_keywords) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">SEO Description</label>
            <textarea name="seo_description" rows="2"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('seo_description', $i?->seo_description) }}</textarea>
        </div>
    </div>

</div>
