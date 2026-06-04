@props([
    'name',
    'items',
    'selected' => [],
    'empty' => 'Nothing here yet.',
])

@if ($items->isEmpty())
    <p class="rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">{{ $empty }}</p>
@else
    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
        @foreach ($items as $item)
            <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm transition hover:border-fuchsia-300">
                <input type="checkbox" name="{{ $name }}[]" value="{{ $item->id }}" @checked(in_array($item->id, $selected, true))
                    class="rounded border-slate-300 text-fuchsia-600 focus:ring-fuchsia-400">
                <span class="truncate text-slate-700">{{ $item->name }}</span>
            </label>
        @endforeach
    </div>
@endif
