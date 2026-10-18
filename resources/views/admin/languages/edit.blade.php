@extends('layouts.admin')

@section('title', 'Languages')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="globe" class="h-5 w-5 text-fuchsia-600" />
            Languages
        </h2>
    </div>

    <div class="space-y-5">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="languages">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Language</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Readiness</th>
                            <th class="px-5 py-3">Preview</th>
                            <th class="px-5 py-3"><span class="sr-only">Switch</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($languages as $code => $language)
                            <tr class="align-top" data-language="{{ $code }}">
                                <td class="px-5 py-4">
                                    <span class="font-medium text-slate-900">{{ $language['label'] }}</span>
                                    <span class="block text-slate-500" lang="{{ $code }}" dir="{{ $language['dir'] }}">{{ $language['name'] }}</span>
                                    <span class="mt-1 inline-block rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase text-slate-500">{{ $language['dir'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($language['enabled'])
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Live
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Preview only
                                        </span>
                                    @endif
                                    @if ($language['changed_at'])
                                        <span class="mt-2 block text-xs text-slate-400">
                                            Switched {{ $language['enabled'] ? 'on' : 'off' }} {{ $language['changed_at']->format('M d, Y H:i') }}{{ $language['changed_by'] ? ' by '.$language['changed_by'] : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($language['is_default'])
                                        <span class="text-slate-500">Source language</span>
                                    @else
                                        @if ($language['has_lang_files'])
                                            <span class="flex items-center gap-1 text-emerald-600"><x-admin.icon name="check-circle" class="h-4 w-4" /> lang/{{ $code }}</span>
                                        @else
                                            <span class="block font-medium text-red-600">lang/{{ $code }} missing</span>
                                        @endif
                                        @if ($language['missing'] === 0 && $language['outdated'] === 0)
                                            <span class="mt-1 block text-emerald-600">Database text done</span>
                                        @else
                                            <a href="{{ route('admin.translations.index') }}" class="mt-1 block text-slate-700 hover:text-fuchsia-700">
                                                {{ $language['missing'] }} missing
                                                @if ($language['outdated'] > 0)
                                                    <span class="text-amber-600">· {{ $language['outdated'] }} outdated</span>
                                                @endif
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($language['previews'] !== [])
                                        <details>
                                            <summary class="inline-flex cursor-pointer list-none items-center gap-1 font-medium text-fuchsia-700 hover:text-fuchsia-800 [&::-webkit-details-marker]:hidden">
                                                <x-admin.icon name="eye" class="h-4 w-4" /> {{ count($language['previews']) }} pages
                                            </summary>
                                            <ul class="mt-2 space-y-1">
                                                @foreach ($language['previews'] as $label => $url)
                                                    <li>
                                                        <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-slate-600 hover:text-fuchsia-700">
                                                            {{ $label }} <x-admin.icon name="external-link" class="h-3.5 w-3.5" />
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($language['is_default'])
                                        <span class="text-xs font-medium text-slate-400">Always on</span>
                                    @else
                                        @php
                                            $confirmMessage = $language['enabled']
                                                ? 'Switch '.$language['label'].' back to preview only? Its pages will return 404 to visitors.'
                                                : 'Switch '.$language['label'].' on for visitors?'.($language['missing'] > 0 ? ' '.$language['missing'].' database texts are missing and will show in English.' : '');
                                        @endphp
                                        <form action="{{ route('admin.languages.toggle', $code) }}" method="POST" onsubmit="return confirm(@js($confirmMessage))">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="enabled" value="{{ $language['enabled'] ? '0' : '1' }}">
                                            @if ($language['enabled'])
                                                <button type="submit" class="whitespace-nowrap rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                                    Switch off
                                                </button>
                                            @else
                                                <button type="submit" @disabled(! $language['has_lang_files'])
                                                    class="whitespace-nowrap rounded-lg bg-fuchsia-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-fuchsia-700 disabled:cursor-not-allowed disabled:opacity-50">
                                                    Switch on
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <form action="{{ route('admin.languages.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-900">Translated service pages</h3>
                    @if ($services->isEmpty())
                        <p class="text-sm text-slate-500">There are no active services.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($services as $service)
                                <li>
                                    <label class="flex items-center gap-2 text-sm text-slate-700">
                                        <input type="checkbox" name="translated_services[]" value="{{ $service->slug }}" class="rounded border-slate-300"
                                            @checked(in_array($service->slug, old('translated_services', $translatedServices), true))>
                                        {{ $service->title }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-900">Language detection</h3>
                    <input type="hidden" name="detect_language" value="0">
                    <label class="flex items-start gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="detect_language" value="1" class="mt-0.5 rounded border-slate-300" @checked(old('detect_language', $detectLanguage))>
                        Send first-time visitors to the live language of their browser, or else of their country
                    </label>
                </section>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="check-circle" class="h-4 w-4" /> Save language settings
                </button>
            </div>
        </form>
    </div>
@endsection
