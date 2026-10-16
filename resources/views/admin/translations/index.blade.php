@extends('layouts.admin')

@section('title', 'Translations')

@section('content')
    @php
        $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
        $pendingItems = $batches->sum(fn ($batch) => $batch->count());
    @endphp

    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="globe" class="h-5 w-5 text-fuchsia-600" />
            Translations
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $pendingItems }} to translate</span>
        </h2>
    </div>

    <div class="space-y-5">
        {{-- 1. Check --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <h3 class="border-b border-slate-100 px-5 py-4 text-sm font-semibold text-slate-900">Check</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="translations-summary">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Area</th>
                            @foreach ($languages as $code)
                                <th class="px-5 py-3">
                                    {{ \App\Support\Locales::label($code) }}
                                    @if (\App\Support\Locales::isEnabled($code))
                                        <span class="ml-1 rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold normal-case text-emerald-700">Live</span>
                                    @else
                                        <span class="ml-1 rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold normal-case text-slate-500">Off</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($summary as $area => $counts)
                            <tr>
                                <td class="px-5 py-3 font-medium text-slate-700">{{ $area }}</td>
                                @foreach ($languages as $code)
                                    @php ['missing' => $missing, 'outdated' => $outdated] = $counts[$code]; @endphp
                                    <td class="px-5 py-3">
                                        @if ($missing === 0 && $outdated === 0)
                                            <span class="inline-flex items-center gap-1 text-emerald-600"><x-admin.icon name="check-circle" class="h-4 w-4" /> Done</span>
                                        @else
                                            <span class="text-slate-700">{{ $missing }} missing</span>
                                            @if ($outdated > 0)
                                                <span class="block text-xs text-amber-600">{{ $outdated }} outdated</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="border-t border-slate-100 px-5 py-3 text-xs text-slate-500">
                Fixed text in the code (menus, buttons, form labels) is translated in the lang/ files, not here.
            </p>
        </section>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            {{-- 2. Export --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Export</h3>
                @if ($batches->isEmpty())
                    <p class="text-sm text-slate-500">Everything is translated in every language.</p>
                @else
                    <p class="mb-4 text-sm text-slate-600">
                        Download a batch, send it to Claude or ChatGPT with the prompt below, then import the file it returns.
                    </p>
                    <ul class="space-y-2">
                        @foreach ($batches as $index => $batch)
                            <li class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 px-4 py-2.5">
                                <span class="text-sm text-slate-700">
                                    Batch {{ $index + 1 }}
                                    <span class="text-slate-400">· {{ $batch->count() }} {{ str('item')->plural($batch->count()) }}</span>
                                </span>
                                <form action="{{ route('admin.translations.export') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="batch" value="{{ $index + 1 }}">
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                                        <x-admin.icon name="download" class="h-4 w-4" /> Download
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            {{-- 4. Import --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Import</h3>
                <p class="mb-4 text-sm text-slate-600">
                    Upload the JSON file the AI returned. You'll see every translation beside its English before anything is saved.
                </p>
                <form action="{{ route('admin.translations.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <input type="file" name="file" accept=".json,application/json,text/plain" required
                        class="block flex-1 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200">
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                        Check file
                    </button>
                </form>
            </section>
        </div>

        {{-- 3. Prompt --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h3 class="text-sm font-semibold text-slate-900">Prompt</h3>
                <button type="button" data-copy-target="translation-prompt"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                    <x-admin.icon name="document-duplicate" class="h-4 w-4" /> <span data-copy-label>Copy prompt</span>
                </button>
            </div>
            <textarea id="translation-prompt" readonly rows="14" class="{{ $input }} font-mono text-xs leading-relaxed text-slate-700">{{ $prompt }}</textarea>
        </section>

        {{-- Glossary --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" id="glossary">
            <h3 class="mb-3 text-sm font-semibold text-slate-900">Glossary</h3>
            <p class="mb-4 text-sm text-slate-600">Terms that must always be translated the same way. They're added to the prompt.</p>

            <form action="{{ route('admin.translations.glossary.store') }}" method="POST" class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-[10rem_1fr_1fr_auto]">
                @csrf
                <select name="locale" class="{{ $input }}" aria-label="Language" required>
                    @foreach ($languages as $code)
                        <option value="{{ $code }}" @selected(old('locale') === $code)>{{ \App\Support\Locales::label($code) }}</option>
                    @endforeach
                </select>
                <input type="text" name="term" value="{{ old('term') }}" placeholder="English term" class="{{ $input }}" maxlength="255" required>
                <input type="text" name="translation" value="{{ old('translation') }}" placeholder="Translation" class="{{ $input }}" maxlength="255" required>
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Add
                </button>
            </form>

            @if ($glossary->isEmpty())
                <p class="text-sm text-slate-400">No terms yet.</p>
            @else
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    @foreach ($glossary as $code => $terms)
                        <div class="overflow-hidden rounded-lg border border-slate-200">
                            <p class="bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ \App\Support\Locales::label($code) }}</p>
                            <ul class="divide-y divide-slate-100">
                                @foreach ($terms as $term)
                                    <li class="flex items-center justify-between gap-3 px-4 py-2 text-sm">
                                        <span class="text-slate-700">{{ $term->term }}</span>
                                        <span class="flex items-center gap-2">
                                            <span class="text-slate-900" dir="auto">{{ $term->translation }}</span>
                                            <x-admin.delete-button :action="route('admin.translations.glossary.destroy', $term)" confirm="Delete this glossary term?" />
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <script>
        document.querySelectorAll('[data-copy-target]').forEach(function (button) {
            button.addEventListener('click', function () {
                var source = document.getElementById(button.getAttribute('data-copy-target'));
                var label = button.querySelector('[data-copy-label]');
                navigator.clipboard.writeText(source.value).then(function () {
                    label.textContent = 'Copied';
                    setTimeout(function () { label.textContent = 'Copy prompt'; }, 2000);
                });
            });
        });
    </script>
@endsection
