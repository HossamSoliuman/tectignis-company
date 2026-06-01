@props(['action', 'confirm' => 'Are you sure you want to delete this item?'])

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm }}')">
    @csrf
    @method('DELETE')
    <button type="submit"
        class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-red-500 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600">
        <x-admin.icon name="trash" class="h-3.5 w-3.5" /> Delete
    </button>
</form>
