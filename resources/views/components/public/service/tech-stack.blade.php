@props(['service'])

@php
    $section = $service->content['tech'] ?? [];
    // Per-service attached technologies, falling back to all active records.
    $techStacks = $service->techStacks->where('is_active', true)->sortBy('sort_order');
    if ($techStacks->isEmpty()) {
        $techStacks = \App\Models\TechStack::active()->ordered()->get();
    }
    $heading = $section['heading'] ?? 'Technologies We Use';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && $techStacks->isNotEmpty())
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        @if (filled($subtitle))
                            <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                        @endif
                        <h3 class="heading">{{ $heading }}</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                @foreach ($techStacks as $tech)
                    <div class="col-lg-2 col-md-3 col-4 wow move-up section-space--mt_30">
                        <div class="ht-box-images style-03 text-center p-3 h-100">
                            @if ($tech->logo)
                                <img class="img-fluid mb-10" src="{{ asset('uploads/'.$tech->logo) }}"
                                    alt="{{ $tech->name }}" loading="lazy" style="max-height:40px;width:auto;display:inline-block;">
                            @endif
                            <p class="font-weight--bold text-color-primary mb-0">{{ $tech->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
