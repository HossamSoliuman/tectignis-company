@props([
    'name',
    'label' => 'Image',
    'current' => null,
    'hint' => 'PNG, JPG, WEBP or SVG',
])

@php
    $previewId = 'preview_'.$name;
@endphp

<div>
    <label class="mb-1 block text-sm font-medium text-slate-700">{{ $label }}</label>

    <div class="flex items-start gap-4">
        {{-- Current / preview thumbnail --}}
        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
            <img id="{{ $previewId }}"
                src="{{ $current ? asset('uploads/'.$current) : '' }}"
                alt="{{ $label }} preview"
                class="h-full w-full object-contain {{ $current ? '' : 'hidden' }}">
            <x-admin.icon name="photograph" class="h-7 w-7 text-slate-300 {{ $current ? 'hidden' : '' }}" data-placeholder />
        </div>

        {{-- Upload control --}}
        <label class="flex flex-1 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 px-4 py-5 text-center transition hover:border-fuchsia-300 hover:bg-fuchsia-50/40">
            <x-admin.icon name="photo-up" class="h-6 w-6 text-fuchsia-500" />
            <span class="mt-1 text-sm font-medium text-slate-600">Click to upload</span>
            <span class="text-xs text-slate-400">{{ $hint }}</span>
            @if ($current)
                <span class="mt-1 text-xs text-slate-400">Current: {{ $current }}</span>
            @endif
            <input type="file" name="{{ $name }}" accept="image/*" class="sr-only"
                data-image-input data-preview="#{{ $previewId }}">
        </label>
    </div>
</div>
