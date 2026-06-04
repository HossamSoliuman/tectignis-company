@props(['service'])

@php
    $section = $service->content['industries'] ?? [];
    // Per-service attached industries, falling back to all active records.
    $industries = $service->industries->where('is_active', true)->sortBy('sort_order');
    if ($industries->isEmpty()) {
        $industries = \App\Models\Industry::active()->ordered()->get();
    }
    $heading = $section['heading'] ?? 'Industries We Serve';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && $industries->isNotEmpty())
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
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
            <div class="row row--30">
                @foreach ($industries as $industry)
                    <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                        <a href="{{ route('industries.show', $industry->slug) }}" class="ht-box-images style-03 d-block text-center p-4 h-100">
                            <div class="image-box-wrap">
                                @if ($industry->icon)
                                    <div class="box-image mb-20">
                                        <i class="{{ $industry->icon }} fa-2x text-color-primary"></i>
                                    </div>
                                @endif
                                <div class="content">
                                    <h6 class="heading">{{ $industry->name }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
