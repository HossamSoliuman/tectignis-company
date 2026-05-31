<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Tectignis Admin</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="h-full text-slate-800">
    <div class="min-h-full">
        {{-- Sidebar --}}
        <aside data-sidebar
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-slate-900 text-slate-200 transition lg:translate-x-0">
            <div class="flex h-16 items-center gap-2 px-6 text-lg font-semibold text-white">
                <span class="inline-block h-3 w-3 rounded-full bg-fuchsia-500"></span> Tectignis
            </div>
            <nav class="space-y-1 px-3 py-4 text-sm">
                @php
                    $nav = [
                        ['admin.dashboard', 'Dashboard'],
                    ];
                @endphp
                @foreach ($nav as [$route, $label])
                    <a href="{{ route($route) }}"
                        class="block rounded-lg px-3 py-2 {{ request()->routeIs($route) ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <p class="px-3 pt-4 text-xs uppercase tracking-wide text-slate-500">More modules arrive as content
                    management is built.</p>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="lg:pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
                <button data-sidebar-toggle class="rounded-lg p-2 hover:bg-slate-100 lg:hidden" aria-label="Toggle menu">
                    <span class="block h-0.5 w-5 bg-slate-700"></span>
                    <span class="mt-1 block h-0.5 w-5 bg-slate-700"></span>
                    <span class="mt-1 block h-0.5 w-5 bg-slate-700"></span>
                </button>
                <h1 class="text-base font-semibold">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500">{{ auth()->user()?->name }}</span>
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-slate-700">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
