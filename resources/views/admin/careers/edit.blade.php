@extends('layouts.admin')

@section('title', 'Careers Page')

@section('content')
    <form action="{{ route('admin.careers-content.update') }}" method="POST" class="max-w-4xl space-y-5">
        @csrf
        @method('PUT')

        {{-- Sticky top bar --}}
        <div class="sticky top-0 z-20 -mx-1 flex items-center justify-between rounded-xl border border-slate-200 bg-white/90 px-4 py-2.5 shadow-sm backdrop-blur">
            <div>
                <h1 class="text-sm font-semibold text-slate-800">Careers Page</h1>
                <p class="text-xs text-slate-400">Stats strip &amp; "Why Join" benefit cards.</p>
            </div>
            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                Save Changes
            </button>
        </div>

        <x-admin.service-panel title="Stats strip"
            subtitle="The row of highlight numbers shown near the top of the careers page.">
            <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">
                Use any <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-fuchsia-600 hover:underline">Font Awesome</a>
                class for the icon, e.g. <code>fas fa-users</code>.
            </p>
            @include('admin.services.partials._repeater', [
                'prefix' => 'stats',
                'rows' => $stats,
                'rowView' => 'admin.careers.partials._stat-row',
                'addLabel' => 'Add stat',
            ])
        </x-admin.service-panel>

        <x-admin.service-panel title='"Why Join" benefit cards'
            subtitle="The grid of benefit cards under the &quot;Why Join Tectignis&quot; heading.">
            <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">
                Use any <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-fuchsia-600 hover:underline">Font Awesome</a>
                class for the icon, e.g. <code>fas fa-chart-line</code>.
            </p>
            @include('admin.services.partials._repeater', [
                'prefix' => 'benefits',
                'rows' => $benefits,
                'rowView' => 'admin.careers.partials._benefit-row',
                'addLabel' => 'Add benefit',
            ])
        </x-admin.service-panel>
    </form>
@endsection
