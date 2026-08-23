@props(['message' => 'Nothing here yet.', 'icon' => 'check-circle'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-2 px-4 py-10 text-center']) }}>
    <x-admin.icon :name="$icon" class="h-8 w-8 text-slate-300" />
    <p class="text-sm text-slate-400">{{ $message }}</p>
</div>
