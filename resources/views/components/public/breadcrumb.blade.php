@props(['title', 'items' => []])

{{--
    Page title band with visible breadcrumbs and BreadcrumbList JSON-LD
    (spec §7, §20 Breadcrumbs). `items` maps label => url; a null url marks
    the current page.
--}}
@php
    $crumbs = collect([['name' => 'Home', 'url' => route('home')]])
        ->merge(collect($items)->map(fn ($url, $label): array => ['name' => (string) $label, 'url' => $url])->values());

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $crumbs->values()->map(fn (array $crumb, int $index): array => array_filter([
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['name'],
            'item' => $crumb['url'] ?? url()->current(),
        ]))->all(),
    ];
@endphp

@push('json-ld')
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_box text-center">
                    <h1 class="breadcrumb-title">{{ $title }}</h1>
                    <nav aria-label="Breadcrumb">
                        <ol class="breadcrumb-list">
                            @foreach ($crumbs as $crumb)
                                @if ($crumb['url'] && ! $loop->last)
                                    <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['name'] }} /</a></li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
