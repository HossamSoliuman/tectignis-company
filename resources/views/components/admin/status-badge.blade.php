@props(['active' => false, 'activeLabel' => 'Active', 'inactiveLabel' => 'Inactive'])

<span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
    {{ $active ? $activeLabel : $inactiveLabel }}
</span>
