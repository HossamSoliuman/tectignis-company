@props([
    'prefix',
    'content' => [],
    'inputClass' => 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400',
])

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
        <input type="text" name="content[{{ $prefix }}][heading]" value="{{ data_get($content, $prefix.'.heading') }}" class="{{ $inputClass }}">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Subtitle</label>
        <input type="text" name="content[{{ $prefix }}][subtitle]" value="{{ data_get($content, $prefix.'.subtitle') }}" class="{{ $inputClass }}">
    </div>
</div>
