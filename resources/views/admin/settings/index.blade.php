@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="mb-5 flex items-center gap-2">
        <x-admin.icon name="cog" class="h-5 w-5 text-fuchsia-600" />
        <h2 class="text-lg font-semibold text-slate-900">Site Settings</h2>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $items)
            <div class="mb-8">
                <h3 class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-fuchsia-500"></span>{{ ucfirst($group) }}
                </h3>
                <div class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100">
                    @foreach ($items as $setting)
                        <div class="flex items-start gap-4 p-4">
                            <label class="w-48 shrink-0 pt-2 text-sm font-medium text-slate-700">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            <div class="flex-1">
                                @if (\App\Models\Setting::isImageKey($setting->key))
                                    <x-admin.image-field
                                        name="images[{{ $setting->key }}]"
                                        label=""
                                        :current="$setting->value"
                                        :current-url="\App\Models\Setting::imageUrl($setting->value, $setting->key)" />
                                @elseif (str_contains($setting->key, 'content') || str_contains($setting->key, 'legal_') || str_contains($setting->key, 'description'))
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
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                Save Settings
            </button>
        </div>
    </form>
@endsection
