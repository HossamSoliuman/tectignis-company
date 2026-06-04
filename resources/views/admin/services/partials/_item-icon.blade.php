{{-- Per-row icon uploader: hidden filename + file input with live preview.
     Reuses the [data-image-field]/[data-image-input] JS used elsewhere in admin. --}}
@php
    $icon = $icon ?? '';
    $src = $icon ? asset('uploads/'.$icon) : '';
@endphp

<div data-image-field class="flex flex-col items-center gap-1">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200 bg-white">
        <img src="{{ $src }}" alt="" class="h-full w-full object-contain {{ $icon ? '' : 'hidden' }}" data-preview-img>
        <x-admin.icon name="photograph" class="h-5 w-5 text-slate-300 {{ $icon ? 'hidden' : '' }}" data-placeholder />
    </div>
    <input type="hidden" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ $icon }}">
    <label class="cursor-pointer text-[11px] font-medium text-fuchsia-600 hover:underline">
        Icon
        <input type="file" name="{{ $prefix }}[{{ $index }}][icon_file]" accept="image/*" class="sr-only" data-image-input>
    </label>
</div>
