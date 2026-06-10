@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $cardMeta = [
            'Total Leads' => ['icon' => 'users', 'route' => 'admin.leads.index', 'chip' => 'bg-purple-100 text-purple-600'],
            'Blog Posts' => ['icon' => 'document-text', 'route' => 'admin.blog.index', 'chip' => 'bg-blue-100 text-blue-600'],
            'Services' => ['icon' => 'briefcase', 'route' => 'admin.services.index', 'chip' => 'bg-emerald-100 text-emerald-600'],
            'Case Studies' => ['icon' => 'photograph', 'route' => 'admin.case-studies.index', 'chip' => 'bg-orange-100 text-orange-600'],
            'Pages' => ['icon' => 'document-duplicate', 'route' => 'admin.pages.index', 'chip' => 'bg-rose-100 text-rose-600'],
        ];
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach ($cards as $label => $card)
            @php $meta = $cardMeta[$label]; @endphp
            <a href="{{ route($meta['route']) }}"
                class="rounded-2xl bg-white p-5 ring-1 ring-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $meta['chip'] }}">
                        <x-admin.icon :name="$meta['icon']" class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-medium text-slate-500">{{ $label }}</span>
                </div>
                <div class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($card['value']) }}</div>
                <div class="mt-2 flex items-center gap-1.5 text-xs">
                    @if ($card['growth'] !== null)
                        <span class="inline-flex items-center gap-0.5 font-semibold {{ $card['growth'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            <x-admin.icon name="arrow-up" class="h-3 w-3 {{ $card['growth'] >= 0 ? '' : 'rotate-180' }}" />
                            {{ number_format(abs($card['growth']), 1) }}%
                        </span>
                        <span class="font-medium text-slate-400">vs last 30 days</span>
                    @else
                        <span class="font-medium text-slate-400">Last 30 days</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    {{-- Website analytics (area chart) + top pages --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200/70 lg:col-span-2">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2.5 text-base font-semibold text-slate-900">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                        <x-admin.icon name="trending-up" class="h-4.5 w-4.5" />
                    </span>
                    Website Analytics
                </h2>
                <span class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-500">Last 30 days</span>
            </div>

            <div class="mt-4">
                <p class="text-xs font-medium text-slate-400">Total Visitors</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">{{ number_format($visitsTotal) }}</span>
                    @if ($visitsGrowth !== null)
                        <span class="inline-flex items-center gap-0.5 text-xs font-semibold {{ $visitsGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            <x-admin.icon name="arrow-up" class="h-3 w-3 {{ $visitsGrowth >= 0 ? '' : 'rotate-180' }}" />
                            {{ number_format(abs($visitsGrowth), 1) }}%
                        </span>
                    @endif
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
                    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="h-48 w-full" preserveAspectRatio="none" role="img" aria-label="Website visitors over the last 30 days">
                        <defs>
                            <linearGradient id="visitsFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#7e36c0" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#7e36c0" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        @for ($g = 0; $g <= 3; $g++)
                            <line x1="{{ $padX }}" y1="{{ $padY + $g * (($h - 2 * $padY) / 3) }}" x2="{{ $w - $padX }}" y2="{{ $padY + $g * (($h - 2 * $padY) / 3) }}" stroke="#f1f5f9" stroke-width="1" />
                        @endfor
                        <path d="{{ $area }}" fill="url(#visitsFill)" />
                        <polyline points="{{ $line }}" fill="none" stroke="#7e36c0" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
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

        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200/70">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2.5 text-base font-semibold text-slate-900">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                        <x-admin.icon name="globe" class="h-4.5 w-4.5" />
                    </span>
                    Top Pages
                </h2>
                <span class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-500">Last 30 days</span>
            </div>

            @if (! empty($topPages))
                @php $topMax = max(1, collect($topPages)->max('value')); @endphp
                <div class="mt-4 flex items-center justify-between border-b border-slate-100 pb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <span>Page</span>
                    <span>Views</span>
                </div>
                <div class="mt-3 space-y-3.5">
                    @foreach ($topPages as $page)
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-medium text-slate-600" title="{{ $page['path'] }}">{{ $page['path'] }}</span>
                                <span class="shrink-0 font-semibold text-slate-900">{{ number_format($page['value']) }}</span>
                            </div>
                            <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-purple-500" style="width: {{ max(4, round($page['value'] / $topMax * 100)) }}%"></div>
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

    {{-- Leads overview (donut) + recent leads + quick actions --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200/70">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2.5 text-base font-semibold text-slate-900">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                        <x-admin.icon name="chart-pie" class="h-4.5 w-4.5" />
                    </span>
                    Leads Overview
                </h2>
                <span class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-500">All time</span>
            </div>

            @if ($leadsTotal > 0)
                @php $segmentColors = ['#7e36c0', '#3b82f6', '#10b981', '#f59e0b', '#94a3b8']; @endphp
                <div class="mt-5 flex flex-col items-center gap-6 sm:flex-row">
                    <div class="relative h-36 w-36 shrink-0">
                        <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90">
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="5" />
                            @php $segmentOffset = 0; @endphp
                            @foreach ($leadSegments as $i => $segment)
                                @php $pct = $segment['value'] / $leadsTotal * 100; @endphp
                                <circle cx="21" cy="21" r="15.915" fill="none" stroke="{{ $segmentColors[$i % count($segmentColors)] }}"
                                    stroke-width="5" stroke-dasharray="{{ round($pct, 2) }} {{ round(100 - $pct, 2) }}"
                                    stroke-dashoffset="{{ round(-$segmentOffset, 2) }}" />
                                @php $segmentOffset += $pct; @endphp
                            @endforeach
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-slate-900">{{ number_format($leadsTotal) }}</span>
                            <span class="text-[11px] font-medium text-slate-400">Total Leads</span>
                        </div>
                    </div>
                    <div class="w-full min-w-0 space-y-2.5">
                        @foreach ($leadSegments as $i => $segment)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $segmentColors[$i % count($segmentColors)] }}"></span>
                                <span class="truncate font-medium text-slate-600">{{ $segment['label'] }}</span>
                                <span class="ml-auto shrink-0 font-semibold text-slate-900">{{ number_format($segment['value']) }}</span>
                                <span class="shrink-0 text-xs text-slate-400">({{ round($segment['value'] / $leadsTotal * 100) }}%)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 py-12 text-center">
                    <x-admin.icon name="chart-pie" class="h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No leads yet.</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200/70">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2.5 text-base font-semibold text-slate-900">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                        <x-admin.icon name="inbox" class="h-4.5 w-4.5" />
                    </span>
                    Recent Leads
                </h2>
                <a href="{{ route('admin.leads.index') }}" class="text-xs font-semibold text-purple-600 transition hover:text-purple-700">View all</a>
            </div>

            @if ($recentLeads->isNotEmpty())
                @php $avatarChips = ['bg-purple-100 text-purple-600', 'bg-blue-100 text-blue-600', 'bg-emerald-100 text-emerald-600', 'bg-orange-100 text-orange-600']; @endphp
                <div class="mt-2 divide-y divide-slate-100">
                    @foreach ($recentLeads as $i => $lead)
                        <a href="{{ route('admin.leads.show', $lead) }}" class="group flex items-center gap-3 py-3">
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold {{ $avatarChips[$i % count($avatarChips)] }}">
                                {{ Str::upper(collect(explode(' ', $lead->name))->filter()->take(2)->map(fn ($part) => Str::substr($part, 0, 1))->implode('')) ?: '?' }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-800 transition group-hover:text-purple-700">{{ $lead->name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ $lead->email }}</p>
                            </div>
                            @if (! $lead->is_read)
                                <span class="shrink-0 rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-semibold text-purple-600 ring-1 ring-purple-100">New</span>
                            @elseif ($lead->source)
                                <span class="shrink-0 rounded-full bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-500 ring-1 ring-slate-100">{{ $lead->source }}</span>
                            @endif
                            <span class="shrink-0 text-[11px] font-medium text-slate-400">{{ $lead->created_at->diffForHumans(short: true) }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 py-12 text-center">
                    <x-admin.icon name="inbox" class="h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">No leads received yet.</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200/70">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                    <x-admin.icon name="plus" class="h-4.5 w-4.5" />
                </span>
                <h2 class="text-base font-semibold text-slate-900">Quick Actions</h2>
            </div>
            <div class="mt-4 space-y-2.5">
                @foreach ([
                    ['admin.blog.create', 'document-text', 'Create Blog Post', 'bg-purple-100 text-purple-600'],
                    ['admin.services.create', 'briefcase', 'Add New Service', 'bg-blue-100 text-blue-600'],
                    ['admin.case-studies.create', 'photograph', 'Upload Case Study', 'bg-emerald-100 text-emerald-600'],
                    ['admin.testimonials.create', 'chat-alt', 'Add Testimonial', 'bg-orange-100 text-orange-600'],
                ] as [$route, $icon, $label, $chip])
                    <a href="{{ route($route) }}"
                        class="group flex items-center gap-3 rounded-xl border border-slate-100 px-3 py-2.5 transition hover:border-purple-200 hover:bg-purple-50/50">
                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $chip }}">
                            <x-admin.icon :name="$icon" class="h-4.5 w-4.5" />
                        </span>
                        <span class="flex-1 text-sm font-medium text-slate-700 transition group-hover:text-purple-700">{{ $label }}</span>
                        <x-admin.icon name="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-purple-400" />
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
