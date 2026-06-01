@props([
    'name',
    'label' => 'Image',
    'current' => null,
    'currentUrl' => null,
    'hint' => 'PNG, JPG, WEBP or SVG',
])

@php
    $previewId = 'preview_'.preg_replace('/[^A-Za-z0-9_]/', '_', $name);
    $currentSrc = $current ? ($currentUrl ?? asset('uploads/'.$current)) : '';
@endphp

<div data-image-field>
    <label class="mb-1 block text-sm font-medium text-slate-700">{{ $label }}</label>

    {{-- Removal flag: toggled to 1 by the Remove button, reset to 0 when a new file is picked. --}}
    <input type="hidden" name="remove_{{ $name }}" value="0" data-remove-flag>

    <div class="flex items-start gap-4">
        {{-- Current / preview thumbnail --}}
        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
            <img id="{{ $previewId }}"
                src="{{ $currentSrc }}"
                alt="{{ $label }} preview"
                class="h-full w-full object-contain {{ $current ? '' : 'hidden' }}"
                data-preview-img>
            <x-admin.icon name="photograph" class="h-7 w-7 text-slate-300 {{ $current ? 'hidden' : '' }}" data-placeholder />
        </div>

        {{-- Upload control --}}
        <label class="flex flex-1 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 px-4 py-5 text-center transition hover:border-fuchsia-300 hover:bg-fuchsia-50/40">
            <x-admin.icon name="photo-up" class="h-6 w-6 text-fuchsia-500" />
            <span class="mt-1 text-sm font-medium text-slate-600">Click to upload</span>
            <span class="text-xs text-slate-400">{{ $hint }}</span>
            <span class="mt-1 text-xs text-slate-400 {{ $current ? '' : 'hidden' }}" data-current-name>Current: {{ $current }}</span>
            <input type="file" name="{{ $name }}" accept="image/*" class="sr-only"
                data-image-input data-preview="#{{ $previewId }}">
        </label>
    </div>

    {{-- Remove control: only meaningful when there is an image to clear. --}}
    <button type="button"
        class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-rose-600 hover:text-rose-700 {{ $current ? '' : 'hidden' }}"
        data-remove-image>
        <x-admin.icon name="trash" class="h-4 w-4" />
        Remove image
    </button>
</div>
