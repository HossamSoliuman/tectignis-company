@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $items)
            <div class="mb-8">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ ucfirst($group) }}</h2>
                <div class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100">
                    @foreach ($items as $setting)
                        <div class="flex items-start gap-4 p-4">
                            <label class="w-48 shrink-0 pt-2 text-sm font-medium text-slate-700">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            <div class="flex-1">
                                @if (str_contains($setting->key, 'content') || str_contains($setting->key, 'legal_') || str_contains($setting->key, 'description'))
                                    <textarea name="settings[{{ $setting->key }}]" rows="6"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ $setting->value }}</textarea>
                                @else
                                    <input type="text" name="settings[{{ $setting->key }}]"
                                        value="{{ $setting->value }}"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit"
                class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
                Save Settings
            </button>
        </div>
    </form>
@endsection
