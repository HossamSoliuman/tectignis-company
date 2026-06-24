@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    @php
        $groupMeta = [
            'general' => ['title' => 'General', 'icon' => 'cog', 'description' => 'Company name, contact details, logos and favicon used across the site.'],
            'home' => ['title' => 'Home Page', 'icon' => 'home', 'description' => 'Headings, sub-headings and images for every section of the home page.'],
            'social' => ['title' => 'Social Media', 'icon' => 'share', 'description' => 'Profile links shown in the footer. Leave a field empty to hide its icon.'],
            'integrations' => ['title' => 'Integrations & Tracking', 'icon' => 'chart-bar', 'description' => 'Google Analytics, Tag Manager, Search Console, Meta Pixel and reCAPTCHA keys.'],
            'smtp' => ['title' => 'Email (SMTP)', 'icon' => 'envelope', 'description' => 'Outgoing mail server. When a host is set, these settings override the default mail configuration.'],
            'legal' => ['title' => 'Legal Pages', 'icon' => 'scale', 'description' => 'HTML content for the Privacy Policy and Terms & Conditions pages.'],
            'seo' => ['title' => 'SEO', 'icon' => 'trending-up', 'description' => 'Search engine directives such as robots.txt.'],
            'careers' => ['title' => 'Careers', 'icon' => 'briefcase', 'description' => 'Careers page content.'],
        ];

        // Show curated groups first (in the order above), then anything new.
        $settings = $settings->sortKeysUsing(function (string $a, string $b) use ($groupMeta): int {
            $order = array_flip(array_keys($groupMeta));

            return ($order[$a] ?? 99) <=> ($order[$b] ?? 99);
        });
    @endphp

    <div class="mb-5 flex items-center gap-2">
        <x-admin.icon name="cog" class="h-5 w-5 text-fuchsia-600" />
        <h2 class="text-lg font-semibold text-slate-900">Site Settings</h2>
    </div>

    {{-- Tab navigation --}}
    <div class="mb-6 flex flex-wrap gap-2" id="settings-tabs">
        @foreach ($settings as $group => $items)
            @php $meta = $groupMeta[$group] ?? ['title' => ucfirst($group), 'icon' => 'cog']; @endphp
            <button type="button"
                data-settings-tab="{{ $group }}"
                class="settings-tab inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-xs font-semibold transition">
                <x-admin.icon :name="$meta['icon']" class="h-3.5 w-3.5" />
                {{ $meta['title'] }}
            </button>
        @endforeach
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $items)
            @php $meta = $groupMeta[$group] ?? ['title' => ucfirst($group), 'icon' => 'cog', 'description' => null]; @endphp
            <div class="mb-8" data-settings-panel="{{ $group }}" hidden>
                <div class="mb-3">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                            <x-admin.icon :name="$meta['icon']" class="h-4 w-4" />
                        </span>
                        {{ $meta['title'] }}
                    </h3>
                    @if ($meta['description'] ?? false)
                        <p class="mt-1 pl-9 text-xs text-slate-400">{{ $meta['description'] }}</p>
                    @endif
                </div>
                <div class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100">
                    @foreach ($items as $setting)
                        <div class="flex items-start gap-4 p-4">
                            <label class="w-48 shrink-0 pt-2 text-sm font-medium text-slate-700">
                                {{ ucwords(str_replace(['_', '-'], ' ', $setting->key)) }}
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
                                @elseif (str_contains($setting->key, 'password') || str_contains($setting->key, 'secret'))
                                    <input type="password" name="settings[{{ $setting->key }}]"
                                        value="{{ $setting->value }}" autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
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

        {{-- Sticky save bar --}}
        <div class="sticky bottom-0 z-10 -mx-4 border-t border-slate-200 bg-white/90 px-4 py-3 backdrop-blur lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-end gap-3">
                <span class="text-xs text-slate-400">Changes apply to the live site immediately after saving.</span>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="check-circle" class="h-4 w-4" />
                    Save Settings
                </button>
            </div>
        </div>
    </form>
@endsection
