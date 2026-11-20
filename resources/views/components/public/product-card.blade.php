@props(['product'])

{{-- One of Tectignis's own SaaS products: what it does, its key features, its website and its case study. --}}
<article {{ $attributes->merge(['class' => 'tx-product-card']) }}>
    <div class="tx-product-card__head">
        <span class="tx-product-card__icon" aria-hidden="true"><i class="{{ $product->icon ?: 'fas fa-cube' }}"></i></span>
        <h3 class="tx-product-card__name">{{ $product->name }}</h3>
    </div>

    <p class="tx-product-card__text">{{ $product->description }}</p>

    @if (! empty($product->features))
        <ul class="tx-product-card__features">
            @foreach ($product->features as $feature)
                <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $feature }}</span></li>
            @endforeach
        </ul>
    @endif

    @if ($product->url || $product->caseStudy)
        <div class="tx-product-card__actions">
            @if ($product->url)
                <a href="{{ $product->url }}" target="_blank" rel="noopener" class="tx-btn tx-btn--light">
                    {{ __('home.product_visit', ['name' => $product->name]) }} <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                </a>
            @endif
            @if ($product->caseStudy)
                <a href="{{ route('case-studies.show', $product->caseStudy->slug) }}" class="tx-product-card__more">
                    {{ __('services.read_case_study') }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    @endif
</article>
