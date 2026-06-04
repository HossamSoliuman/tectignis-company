{{-- "Show this section" toggle bound to content[<section>][enabled].
     A hidden 0 precedes the checkbox so unchecking submits false. --}}
@php $enabled = data_get($content ?? [], $section.'.enabled', $default ?? true); @endphp

<label class="inline-flex cursor-pointer items-center gap-2">
    <input type="hidden" name="content[{{ $section }}][enabled]" value="0">
    <input type="checkbox" name="content[{{ $section }}][enabled]" value="1" {{ $enabled ? 'checked' : '' }}
        class="rounded border-slate-300 text-fuchsia-600 focus:ring-fuchsia-400">
    <span class="text-sm font-medium text-slate-700">Show this section</span>
</label>
