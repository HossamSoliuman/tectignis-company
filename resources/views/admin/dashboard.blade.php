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

    {{-- Page visits trend (area chart) + top pages --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-2">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                        <x-admin.icon name="trending-up" class="h-5 w-5 text-fuchsia-600" /> Page visits
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">Last 30 days</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-slate-900">{{ number_format($visitsTotal) }}</div>
                    <div class="text-xs font-medium text-slate-400">total visits</div>
                </div>
            </div>

            @php
                $w = 720; $h = 220; $padX = 8; $padY = 16;
                $n = count($visitsTrend);
                $max = max(1, collect($visitsTrend)->max('value'));
                $stepX = $n > 1 ? ($w - 2 * $padX) / ($n - 1) : 0;
                $x = fn (int $i): float => round($padX + $i * $stepX, 2);
                $y = fn (int $v): float => round($h - $padY - ($v / $max) * ($h - 2 * $padY), 2);
                $line = collect($visitsTrend)->map(fn ($d, $i) => $x($i) . ',' . $y($d['value']))->implode(' ');
                $area = 'M ' . $x(0) . ',' . ($h - $padY) . ' L ' . str_replace(' ', ' L ', $line) . ' L ' . $x($n - 1) . ',' . ($h - $padY) . ' Z';
            @endphp

            @if ($visitsTotal > 0)
                <div class="mt-4">
                    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="h-48 w-full" preserveAspectRatio="none" role="img" aria-label="Page visits over the last 30 days">
                        <defs>
                            <linearGradient id="visitsFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#d946ef" stop-opacity="0.28" />
                                <stop offset="100%" stop-color="#d946ef" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        @for ($g = 0; $g <= 3; $g++)
                            <line x1="{{ $padX }}" y1="{{ $padY + $g * (($h - 2 * $padY) / 3) }}" x2="{{ $w - $padX }}" y2="{{ $padY + $g * (($h - 2 * $padY) / 3) }}" stroke="#f1f5f9" stroke-width="1" />
                        @endfor
                        <path d="{{ $area }}" fill="url(#visitsFill)" />
                        <polyline points="{{ $line }}" fill="none" stroke="#c026d3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                    </svg>
                    <div class="mt-2 flex justify-between text-[11px] font-medium text-slate-400">
                        <span>{{ $visitsTrend[0]['label'] }}</span>
                        <span>{{ $visitsTrend[intdiv($n, 2)]['label'] }}</span>
                        <span>{{ $visitsTrend[$n - 1]['label'] }}</span>
                    </div>
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 py-12 text-center">
                    <x-admin.icon name="trending-up" class="h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No visits recorded yet. Data appears as people browse the site.</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                <x-admin.icon name="globe" class="h-5 w-5 text-sky-600" /> Top pages
            </h2>
            <p class="mt-1 text-sm text-slate-500">Most visited, last 30 days</p>

            @if (! empty($topPages))
                @php $topMax = max(1, collect($topPages)->max('value')); @endphp
                <div class="mt-4 space-y-3">
                    @foreach ($topPages as $page)
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-medium text-slate-600" title="{{ $page['path'] }}">{{ $page['path'] }}</span>
                                <span class="shrink-0 font-semibold text-slate-900">{{ number_format($page['value']) }}</span>
                            </div>
                            <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600" style="width: {{ max(4, round($page['value'] / $topMax * 100)) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 py-10 text-center">
                    <x-admin.icon name="globe" class="h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No page data yet.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Leads trend (bar chart) + quick actions --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-2">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                        <x-admin.icon name="chart-bar" class="h-5 w-5 text-emerald-600" /> Leads received
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">Last 30 days</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-slate-900">{{ number_format($leadsTotal) }}</div>
                    <div class="text-xs font-medium text-slate-400">new leads</div>
                </div>
            </div>

            @php $leadsMax = max(1, collect($leadsTrend)->max('value')); $ln = count($leadsTrend); @endphp
            @if ($leadsTotal > 0)
                <div class="mt-5 flex h-40 items-end gap-[3px]">
                    @foreach ($leadsTrend as $day)
                        <div class="group relative flex-1">
                            <div class="w-full rounded-t bg-gradient-to-t from-emerald-500 to-teal-400 transition group-hover:from-emerald-600 group-hover:to-teal-500"
                                style="height: {{ $day['value'] > 0 ? max(4, round($day['value'] / $leadsMax * 150)) : 1 }}px"></div>
                            <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 hidden -translate-x-1/2 whitespace-nowrap rounded-md bg-slate-900 px-2 py-1 text-[11px] font-medium text-white shadow group-hover:block">
                                {{ $day['value'] }} on {{ $day['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 flex justify-between text-[11px] font-medium text-slate-400">
                    <span>{{ $leadsTrend[0]['label'] }}</span>
                    <span>{{ $leadsTrend[intdiv($ln, 2)]['label'] }}</span>
                    <span>{{ $leadsTrend[$ln - 1]['label'] }}</span>
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 py-12 text-center">
                    <x-admin.icon name="chart-bar" class="h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No leads in the last 30 days.</p>
                </div>
            @endif
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
