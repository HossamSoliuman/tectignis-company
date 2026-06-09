<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Tectignis Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=Poppins:wght@500;600;700;800&amp;display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="h-full text-slate-800">
    <div class="min-h-full">
        {{-- Sidebar --}}
        <aside data-sidebar
            class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col bg-slate-900 text-slate-300 shadow-xl transition-transform duration-200 lg:translate-x-0">
            <div class="flex h-16 items-center gap-2.5 border-b border-white/5 px-6 text-lg font-semibold text-white">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-fuchsia-500 to-purple-600 text-sm font-bold shadow-lg shadow-fuchsia-500/30">T</span>
                Tectignis
            </div>
            <nav class="flex-1 space-y-4 overflow-y-auto px-3 py-4 text-sm">
                @php
                    $navGroups = [
                        '' => [
                            ['admin.dashboard', 'Dashboard', 'grid'],
                        ],
                        'Website Management' => [
                            ['admin.settings.index', 'Home', 'home'],
                            ['admin.stats.index', 'Stats', 'chart-pie'],
                            ['admin.pages.index', 'Pages', 'document-duplicate'],
                            ['admin.blog.index', 'Blog Posts', 'document-text'],
                            ['admin.case-studies.index', 'Case Studies', 'photograph'],
                            ['admin.testimonials.index', 'Testimonials', 'chat-alt'],
                            ['admin.brands.index', 'Trusted Technology Partner', 'tag'],
                        ],
                        'Services & Capabilities' => [
                            ['admin.capabilities.index', 'Capabilities', 'cube'],
                            ['admin.services.index', 'Services', 'briefcase'],
                            ['admin.solutions.index', 'Solutions', 'puzzle'],
                            ['admin.industries.index', 'Industries', 'office'],
                            ['admin.tech-stacks.index', 'Tech Stacks', 'chip'],
                        ],
                        'Home Sections' => [
                            ['admin.why-choose-features.index', 'Why Choose Us', 'check-circle'],
                            ['admin.office-locations.index', 'Office Locations', 'office'],
                            ['admin.global-advantages.index', 'Global Advantages', 'globe'],
                            ['admin.process-steps.index', 'Process Steps', 'trending-up'],
                        ],
                        'Sales & Marketing' => [
                            ['admin.leads.index', 'Leads (Inbox)', 'inbox'],
                            ['admin.redirects.index', 'Redirects', 'switch-horizontal'],
                        ],
                        'Administration' => [
                            ['admin.settings.index', 'Settings', 'cog'],
                        ],
                    ];
                @endphp
                @foreach ($navGroups as $groupLabel => $items)
                    <div class="space-y-0.5">
                        @if ($groupLabel !== '')
                            <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">{{ $groupLabel }}</p>
                        @endif
                        @foreach ($items as [$route, $label, $icon])
                            @php $active = request()->routeIs(Str::endsWith($route, '.index') ? Str::beforeLast($route, '.').'.*' : $route); @endphp
                            <a href="{{ route($route) }}"
                                class="group flex items-center gap-3 rounded-lg px-3 py-2 font-medium transition {{ $active ? 'bg-gradient-to-r from-fuchsia-600/90 to-purple-600/80 text-white shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <x-admin.icon :name="$icon" class="h-5 w-5 shrink-0 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-fuchsia-300' }}" />
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </nav>
            <div class="border-t border-white/5 px-3 py-4">
                <a href="{{ url('/') }}" target="_blank"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-400 transition hover:bg-white/5 hover:text-white">
                    <x-admin.icon name="external-link" class="h-5 w-5 shrink-0 text-slate-500" />
                    View live site
                </a>
            </div>
        </aside>

        {{-- Mobile backdrop --}}
        <div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        {{-- Main --}}
        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur lg:px-8">
                <div class="flex items-center gap-3">
                    <button data-sidebar-toggle class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                        aria-label="Toggle menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-base font-semibold text-slate-900">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank"
                        class="hidden items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm text-slate-500 hover:bg-slate-100 hover:text-slate-700 sm:inline-flex">
                        <x-admin.icon name="external-link" class="h-4 w-4" /> View site
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-xs font-semibold text-white">
                            {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </span>
                        <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ auth()->user()?->name }}</span>
                    </div>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button
                            class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-slate-700">
                            <x-admin.icon name="logout" class="h-4 w-4" />
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @if (session('status'))
                    <div
                        class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <x-admin.icon name="check-circle" class="h-5 w-5 shrink-0 text-emerald-500" />
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <x-admin.icon name="default" class="h-5 w-5 shrink-0 text-red-500" />
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
