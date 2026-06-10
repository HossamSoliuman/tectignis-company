<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

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
            class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0">
            <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-slate-100 px-6 text-lg font-semibold text-slate-900">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-fuchsia-500 to-purple-600 text-sm font-bold text-white shadow-lg shadow-purple-600/30">T</span>
                Tectignis
            </div>
            <nav class="sidebar-nav flex-1 space-y-4 overflow-y-auto px-3 py-4 text-sm">
                @php
                    $navGroups = [
                        '' => [
                            ['admin.dashboard', 'Dashboard', 'grid'],
                        ],
                        'Website Management' => [
                            ['admin.settings.index', 'Home', 'home'],
                            ['admin.stats.index', 'Stats', 'chart-pie'],
                            ['admin.pages.index', 'Pages', 'document-duplicate'],
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
                        'Resources' => [
                            ['admin.blog.index', 'Blog Posts', 'document-text'],
                            ['admin.insights.index', 'Technology Insights', 'light-bulb'],
                            ['admin.faqs.index', 'FAQs', 'question-mark-circle'],
                            ['admin.faq-categories.index', 'FAQ Categories', 'folder'],
                            ['admin.downloads.index', 'Downloads', 'download'],
                            ['admin.job-openings.index', 'Job Openings', 'briefcase'],
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
                            <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400">{{ $groupLabel }}</p>
                        @endif
                        @foreach ($items as [$route, $label, $icon])
                            @php $active = request()->routeIs(Str::endsWith($route, '.index') ? Str::beforeLast($route, '.').'.*' : $route); @endphp
                            <a href="{{ route($route) }}"
                                class="group flex items-center gap-3 rounded-lg px-3 py-2 font-medium transition {{ $active ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <x-admin.icon :name="$icon" class="h-5 w-5 shrink-0 {{ $active ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-600' }}" />
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </nav>
            <div class="shrink-0 space-y-2 border-t border-slate-100 px-3 py-3">
                <a href="{{ url('/') }}" target="_blank"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    <x-admin.icon name="external-link" class="h-5 w-5 shrink-0 text-slate-400" />
                    View live site
                </a>
                <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-xs font-semibold text-white">
                        {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()?->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()?->email }}</p>
                    </div>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white hover:text-purple-600" title="Logout" aria-label="Logout">
                            <x-admin.icon name="logout" class="h-4.5 w-4.5" />
                        </button>
                    </form>
                </div>
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
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ url('/') }}" target="_blank"
                        class="hidden items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm text-slate-500 hover:bg-slate-100 hover:text-slate-700 sm:inline-flex">
                        <x-admin.icon name="external-link" class="h-4 w-4" /> View site
                    </a>
                    @php $unreadLeads = \App\Models\Lead::where('is_read', false)->count(); @endphp
                    <a href="{{ route('admin.leads.index') }}" class="relative rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        title="Leads inbox" aria-label="Leads inbox{{ $unreadLeads ? ', ' . $unreadLeads . ' unread' : '' }}">
                        <x-admin.icon name="bell" class="h-5 w-5" />
                        @if ($unreadLeads > 0)
                            <span class="absolute right-0.5 top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-semibold text-white">
                                {{ $unreadLeads > 9 ? '9+' : $unreadLeads }}
                            </span>
                        @endif
                    </a>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-xs font-semibold text-white">
                            {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </span>
                        <div class="hidden leading-tight sm:block">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()?->name }}</p>
                            <p class="text-xs text-slate-400">Super Admin</p>
                        </div>
                    </div>
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

                <footer class="mt-10 flex items-center justify-between border-t border-slate-200/70 pt-4 text-xs text-slate-400">
                    <span>© {{ now()->year }} Tectignis. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </footer>
            </main>
        </div>
    </div>
</body>

</html>
