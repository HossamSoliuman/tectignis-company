@props(['href'])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-600 transition hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700']) }}>
    <x-admin.icon name="pencil" class="h-3.5 w-3.5" /> Edit
</a>
