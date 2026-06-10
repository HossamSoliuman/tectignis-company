@php $f = $faq ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Category *</label>
        <select name="faq_category_id" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            <option value="" disabled {{ old('faq_category_id', $f?->faq_category_id) ? '' : 'selected' }}>Choose a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('faq_category_id', $f?->faq_category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-400">Manage categories under <a href="{{ route('admin.faq-categories.index') }}" class="text-fuchsia-600 hover:underline">FAQ Categories</a>.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Question *</label>
        <input type="text" name="question" value="{{ old('question', $f?->question) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Answer *</label>
        <textarea name="answer" rows="5" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('answer', $f?->answer) }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $f?->sort_order ?? 0) }}" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex items-center gap-2 pt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $f?->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</div>
