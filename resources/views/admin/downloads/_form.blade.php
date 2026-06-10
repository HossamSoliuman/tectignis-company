@php $d = $download ?? null; @endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $d?->title) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category *</label>
            <select name="category" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach (\App\Models\Download::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $d?->category ?? 'brochure') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">File Type *</label>
            <select name="file_type" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach (\App\Models\Download::FILE_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('file_type', $d?->file_type ?? 'pdf') === $type)>{{ strtoupper($type) }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-400">Shown as the file badge on the public downloads page.</p>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea name="description" rows="3"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $d?->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">File</label>
        @if ($d?->file)
            <div class="mb-2 flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">
                <x-admin.icon name="download" class="h-4 w-4 text-slate-400" />
                <a href="{{ asset('uploads/'.$d->file) }}" target="_blank" class="text-fuchsia-600 hover:underline">{{ basename($d->file) }}</a>
            </div>
            <label class="mb-2 flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remove_file" value="1" class="rounded border-slate-300"> Remove current file
            </label>
        @endif
        <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-fuchsia-50 file:px-3 file:py-1 file:text-sm file:font-medium file:text-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <p class="mt-1 text-xs text-slate-400">PDF, DOC, PPT, XLS or ZIP — max 20 MB. Visitors receive this file after submitting the download form.</p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $d?->sort_order ?? 0) }}" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex items-center gap-2 pt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $d?->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</div>
