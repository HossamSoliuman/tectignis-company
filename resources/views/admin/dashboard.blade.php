@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $cards = [
            'Leads' => ['icon' => 'inbox', 'route' => 'admin.leads.index', 'from' => 'from-sky-500', 'to' => 'to-blue-600'],
            'Blog Posts' => ['icon' => 'document-text', 'route' => 'admin.blog.index', 'from' => 'from-fuchsia-500', 'to' => 'to-purple-600'],
            'Services' => ['icon' => 'briefcase', 'route' => 'admin.services.index', 'from' => 'from-emerald-500', 'to' => 'to-teal-600'],
            'Case Studies' => ['icon' => 'photograph', 'route' => 'admin.case-studies.index', 'from' => 'from-amber-500', 'to' => 'to-orange-600'],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
            @php $meta = $cards[$label] ?? ['icon' => 'grid', 'route' => 'admin.dashboard', 'from' => 'from-slate-500', 'to' => 'to-slate-700']; @endphp
            <a href="{{ route($meta['route']) }}"
                class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">{{ $label }}</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</div>
                    </div>
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $meta['from'] }} {{ $meta['to'] }} text-white shadow-lg">
                        <x-admin.icon :name="$meta['icon']" class="h-6 w-6" />
                    </span>
                </div>
                <span class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-slate-400 transition group-hover:text-fuchsia-600">
                    Manage <x-admin.icon name="arrow-left" class="h-3.5 w-3.5 rotate-180" />
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-2">
            <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                <x-admin.icon name="grid" class="h-5 w-5 text-fuchsia-600" /> Welcome to the Tectignis admin
            </h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                Manage your website content from here — services, blog posts, case studies, testimonials, brands,
                leads and site settings. Uploaded images are stored in the <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-fuchsia-700">public/uploads</code> folder.
            </p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                <x-admin.icon name="plus" class="h-5 w-5 text-emerald-600" /> Quick actions
            </h2>
            <div class="mt-4 space-y-2">
                @foreach ([['admin.services.create', 'briefcase', 'New service'], ['admin.blog.create', 'document-text', 'New blog post'], ['admin.case-studies.create', 'photograph', 'New case study'], ['admin.brands.create', 'tag', 'New brand']] as [$r, $i, $l])
                    <a href="{{ route($r) }}"
                        class="flex items-center gap-3 rounded-lg border border-slate-100 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-fuchsia-200 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                        <x-admin.icon :name="$i" class="h-4 w-4" /> {{ $l }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
