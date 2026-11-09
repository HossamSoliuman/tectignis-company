@props(['title', 'icon'])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white']) }}>
    <h2 class="flex items-center gap-2.5 border-b border-slate-100 px-5 py-3.5 text-sm font-semibold text-slate-800 sm:px-6">
        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
            <x-admin.icon :name="$icon" class="h-4 w-4" />
        </span>
        {{ $title }}
    </h2>
    <div class="space-y-5 p-5 sm:p-6">
        {{ $slot }}
    </div>
</section>
