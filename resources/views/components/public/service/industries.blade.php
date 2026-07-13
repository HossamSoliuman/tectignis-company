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
    <section class="svc-section svc-industries">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-industries__grid">
                @foreach ($industries as $industry)
                    <div class="col-lg-2 col-md-4 col-6 wow move-up">
                        <a href="{{ route('industries.show', $industry->slug) }}" class="svc-industries__card">
                            <span class="svc-industries__icon">
                                @if ($industry->icon)
                                    <i class="{{ $industry->icon }}"></i>
                                @endif
                            </span>
                            <div class="svc-industries__body">
                                <h3 class="svc-industries__title">{{ $industry->name }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
