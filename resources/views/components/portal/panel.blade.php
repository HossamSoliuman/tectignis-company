@props(['title', 'icon' => null, 'count' => null, 'action' => null, 'actionLabel' => 'View all'])

<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm']) }}>
    <header class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
        <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900">
            @if ($icon)
                <x-admin.icon :name="$icon" class="h-4 w-4 text-fuchsia-600" />
            @endif
            {{ $title }}
            @if (! is_null($count))
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $count }}</span>
            @endif
        </h3>
        @if ($action)
            <a href="{{ $action }}" class="text-xs font-medium text-fuchsia-600 hover:underline">{{ $actionLabel }}</a>
        @endif
    </header>
    {{ $slot }}
</section>
