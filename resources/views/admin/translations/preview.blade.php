@extends('layouts.admin')

@section('title', 'Import Translations')

@section('content')
    @php
        $input = 'w-full rounded-lg border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
        $entryCount = $items->sum(fn ($entries) => $entries->count());
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.translations.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Translations
        </a>
        <span class="text-sm text-slate-500">
            Export {{ $export->export_id }} · {{ $entryCount }} {{ str('translation')->plural($entryCount) }}
            @if ($problemCount > 0)
                · <span class="font-medium text-amber-700">{{ $problemCount }} with problems</span>
            @endif
        </span>
    </div>

    @if ($items->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500 shadow-sm">
            The file has no filled-in translations.
        </div>
    @else
        <form action="{{ route('admin.translations.confirm') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="export_id" value="{{ $export->export_id }}">

            @if ($problemCount > 0)
                <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Translations with a problem are not saved. Fix them here and check again, or save the rest and they'll come back in the next check.
                </p>
            @endif

            @foreach ($items as $id => $entries)
                @php $item = $entries->first()['item']; @endphp
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-3 flex flex-wrap items-baseline justify-between gap-2">
                        <h3 class="text-sm font-semibold text-slate-900">{{ $item?->context ?? $id }}</h3>
                        <span class="font-mono text-xs text-slate-400">{{ $id }}</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div>
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">English</p>
                            <div class="max-h-64 overflow-y-auto whitespace-pre-wrap rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $item?->source }}</div>
                            @if ($item?->maxLength)
                                <p class="mt-1 text-xs text-slate-400">Up to {{ $item->maxLength }} characters</p>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @foreach ($entries as $entry)
                                @php $fieldId = 'value-'.md5($id.$entry['locale']); @endphp
                                <div>
                                    <label for="{{ $fieldId }}" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        {{ \App\Support\Locales::label($entry['locale']) }}
                                    </label>
                                    <textarea id="{{ $fieldId }}" name="values[{{ $id }}][{{ $entry['locale'] }}]"
                                        rows="{{ min(12, max(2, (int) ceil(mb_strlen($entry['value']) / 70))) }}"
                                        dir="{{ \App\Support\Locales::direction($entry['locale']) }}" lang="{{ $entry['locale'] }}"
                                        class="{{ $input }} {{ $entry['error'] ? 'border-amber-400' : 'border-slate-300' }}">{{ $entry['value'] }}</textarea>
                                    @if ($entry['error'])
                                        <p class="mt-1 text-xs font-medium text-amber-700">{{ $entry['error'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach

            <div class="sticky bottom-0 flex flex-wrap items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white/95 px-5 py-3 shadow-sm backdrop-blur">
                <a href="{{ route('admin.translations.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
                @if ($problemCount > 0)
                    <button type="submit" name="review" value="1"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Check again
                    </button>
                @endif
                <button type="submit" class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Save translations
                </button>
            </div>
        </form>
    @endif
@endsection
