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
            <nav class="sidebar-nav flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm">
                @php
                    // Two products share this shell. CMS groups are hidden from
                    // portal-only staff, and Operations is hidden from anyone
                    // without a portal role — nobody sees a link they'd get a
                    // 403 from. Leads live outside the CMS so Sales and
                    // Read-only users (spec §26.7) get them without content rights.
                    $navUser = auth()->user();
                    $showCms = (bool) $navUser?->isAdmin();
                    $showLeads = (bool) $navUser?->canViewLeads();
                    $showSuperAdmin = (bool) $navUser?->isSuperAdmin();
                    $showPortal = (bool) $navUser?->hasPortalAccess();

                    // Group items are [route, label, extra route patterns that also mark it active].
                    $isActive = fn (string $route, array $alsoActiveOn = []): bool => request()->routeIs(
                        Str::endsWith($route, '.index') ? Str::beforeLast($route, '.').'.*' : $route,
                        ...$alsoActiveOn,
                    );

                    $topLinks = array_filter([
                        $showCms ? ['admin.dashboard', 'Dashboard', 'grid'] : null,
                        $showLeads ? ['admin.leads.index', 'Leads', 'inbox'] : null,
                    ]);

                    $navGroups = array_values(array_filter([
                        $showPortal ? ['Operations', 'clipboard-list', [
                            ['admin.portal.dashboard', 'Overview'],
                            ['admin.portal.my-work', 'My Work'],
                            ['admin.portal.tasks.index', 'Tasks'],
                            ['admin.portal.tenders.index', 'Tenders'],
                            ['admin.portal.oem-followups.index', 'OEM Follow-ups'],
                            ['admin.portal.daily-work.index', 'Daily Work'],
                            ['admin.portal.employees.index', 'Employees'],
                            ['admin.portal.departments.index', 'Departments'],
                        ]] : null,
                        $showCms ? ['Homepage', 'home', [
                            ['admin.stats.index', 'Stats'],
                            ['admin.why-choose-features.index', 'Why Choose Us'],
                            ['admin.process-steps.index', 'Process Steps'],
                            ['admin.global-advantages.index', 'Global Advantages'],
                            ['admin.office-locations.index', 'Office Locations'],
                            ['admin.testimonials.index', 'Testimonials'],
                            ['admin.brands.index', 'Technology Partners'],
                        ]] : null,
                        $showCms ? ['What We Offer', 'cube', [
                            ['admin.services.index', 'Services'],
                            ['admin.solutions.index', 'Solutions'],
                            ['admin.capabilities.index', 'Capabilities'],
                            ['admin.industries.index', 'Industries'],
                            ['admin.tech-stacks.index', 'Tech Stacks'],
                        ]] : null,
                        $showCms ? ['Content', 'document-text', [
                            ['admin.pages.index', 'Pages'],
                            ['admin.blog.index', 'Blog Posts'],
                            ['admin.insights.index', 'Insights'],
                            ['admin.case-studies.index', 'Case Studies', ['admin.case-study-categories.*']],
                            ['admin.faqs.index', 'FAQs', ['admin.faq-categories.*']],
                            ['admin.downloads.index', 'Downloads'],
                        ]] : null,
                        $showCms ? ['Careers', 'briefcase', [
                            ['admin.job-openings.index', 'Job Openings'],
                            ['admin.careers-content.edit', 'Careers Page'],
                        ]] : null,
                        ($showCms || $showSuperAdmin) ? ['Settings', 'cog', array_merge(
                            $showCms ? [
                                ['admin.settings.index', 'Site Settings'],
                                ['admin.mail.edit', 'Email & Forms'],
                                ['admin.redirects.index', 'Redirects'],
                            ] : [],
                            $showSuperAdmin ? [
                                ['admin.users.index', 'Users & Roles'],
                                ['admin.captcha.edit', 'CAPTCHA'],
                            ] : [],
                        )] : null,
                    ]));
                @endphp

                @foreach ($topLinks as [$route, $label, $icon])
                    @php $active = $isActive($route); @endphp
                    <a href="{{ route($route) }}"
                        class="group flex items-center gap-3 rounded-lg px-3 py-2 font-medium transition {{ $active ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <x-admin.icon :name="$icon" class="h-5 w-5 shrink-0 {{ $active ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-600' }}" />
                        <span>{{ $label }}</span>
                    </a>
                @endforeach

                @foreach ($navGroups as [$groupLabel, $groupIcon, $items])
                    @php $groupActive = collect($items)->contains(fn (array $item): bool => $isActive($item[0], $item[2] ?? [])); @endphp
                    <details class="group/nav" @if ($groupActive || count($navGroups) === 1) open @endif>
                        <summary
                            class="group flex cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 font-medium transition [&::-webkit-details-marker]:hidden {{ $groupActive ? 'text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <x-admin.icon :name="$groupIcon" class="h-5 w-5 shrink-0 {{ $groupActive ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-600' }}" />
                            <span class="flex-1">{{ $groupLabel }}</span>
                            <x-admin.icon name="chevron-right" class="h-4 w-4 shrink-0 text-slate-400 transition-transform group-open/nav:rotate-90" />
                        </summary>
                        <div class="ml-5 mt-0.5 space-y-0.5 border-l border-slate-200 py-0.5 pl-3">
                            @foreach ($items as $item)
                                @php $active = $isActive($item[0], $item[2] ?? []); @endphp
                                <a href="{{ route($item[0]) }}"
                                    class="block rounded-lg px-3 py-1.5 transition {{ $active ? 'bg-purple-50 font-medium text-purple-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                    {{ $item[1] }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </nav>
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
                    @if (auth()->user()?->canViewLeads())
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
                    @endif
                    <a href="{{ route('admin.account.edit') }}" class="flex items-center gap-2.5 rounded-lg px-1.5 py-1 transition hover:bg-slate-100" title="My account">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-xs font-semibold text-white">
                            {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </span>
                        <div class="hidden leading-tight sm:block">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()?->name }}</p>
                            <p class="text-xs text-slate-400">
                                @php $headerRole = auth()->user()?->userRole(); @endphp
                                {{ $headerRole && $headerRole !== \App\Enums\UserRole::PortalOnly
                                    ? $headerRole->label()
                                    : (auth()->user()?->portalRole()?->label() ?? 'User') }}
                            </p>
                        </div>
                    </a>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                            title="Logout" aria-label="Logout">
                            <x-admin.icon name="logout" class="h-5 w-5" />
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

                <footer class="mt-10 flex items-center justify-between border-t border-slate-200/70 pt-4 text-xs text-slate-400">
                    <span>© {{ now()->year }} Tectignis. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </footer>
            </main>
        </div>
    </div>
</body>

</html>
