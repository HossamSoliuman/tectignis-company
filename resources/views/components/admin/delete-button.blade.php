@props(['action', 'confirm' => 'Are you sure you want to delete this item?', 'iconOnly' => false])

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm }}')">
    @csrf
    @method('DELETE')
    @if ($iconOnly)
        <button type="submit" title="Delete" aria-label="Delete"
            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
            <x-admin.icon name="trash" class="h-4 w-4" />
        </button>
    @else
        <button type="submit"
            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-red-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600">
            <x-admin.icon name="trash" class="h-3.5 w-3.5" /> Delete
        </button>
    @endif
</form>
