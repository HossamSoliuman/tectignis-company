{{-- Reorder / remove controls shared by every repeater row. --}}
<div class="flex shrink-0 flex-col items-center gap-1 pt-0.5">
    <button type="button" data-repeater-up title="Move up"
        class="rounded p-1 text-slate-400 transition hover:bg-slate-200 hover:text-slate-700">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
    </button>
    <button type="button" data-repeater-down title="Move down"
        class="rounded p-1 text-slate-400 transition hover:bg-slate-200 hover:text-slate-700">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <button type="button" data-repeater-remove title="Remove"
        class="rounded p-1 text-rose-400 transition hover:bg-rose-50 hover:text-rose-600">
        <x-admin.icon name="trash" class="h-4 w-4" />
    </button>
</div>
