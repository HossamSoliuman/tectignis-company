@props(['solution'])

@php
    $section = $solution->content['industries'] ?? [];
    $industries = \App\Models\Industry::active()->ordered()->limit(8)->get();
    $heading = $section['heading'] ?? 'Solutions for Every Industry';
    $subtitle = $section['subtitle'] ?? null;
    $ctaLabel = $section['cta_label'] ?? 'View All Industries';
@endphp

@if (($section['enabled'] ?? true) && $industries->isNotEmpty())
    <section class="svc-section ind-grid-section">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="ind-grid">
                @foreach ($industries as $industry)
                    <a href="{{ route('industries.show', $industry->slug) }}" class="ind-grid__item wow move-up">
                        <span class="ind-grid__icon">
                            <i class="{{ $industry->icon ?: 'fas fa-cube' }}"></i>
                        </span>
                        <span class="ind-grid__label">{{ $industry->name }}</span>
                    </a>
                @endforeach
            </div>

            <div class="text-center" style="margin-top: 44px;">
                <a href="{{ route('industries.index') }}" class="svc-btn svc-btn--primary">{{ $ctaLabel }} <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endif
