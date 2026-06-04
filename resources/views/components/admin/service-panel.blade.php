@props([
    'title',
    'subtitle' => null,
    'note' => null,
    'section' => null,
    'content' => [],
    'open' => true,
])

<details class="group rounded-xl border border-slate-200 bg-white" @if ($open) open @endif>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 [&::-webkit-details-marker]:hidden">
        <span class="text-sm font-semibold text-slate-800">{{ $title }}</span>
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </summary>
    <div class="space-y-4 border-t border-slate-100 px-5 py-4">
        @if ($section)
            @include('admin.services.partials._section-toggle', ['section' => $section, 'content' => $content])
        @endif
        @if ($subtitle)
            <p class="text-xs text-slate-400">{{ $subtitle }}</p>
        @endif
        @if ($note)
            <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">{{ $note }}</p>
        @endif
        {{ $slot }}
    </div>
</details>
