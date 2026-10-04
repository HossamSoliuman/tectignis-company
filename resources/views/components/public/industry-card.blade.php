@props(['industry'])

{{-- An industry tile linking to its dedicated page (spec §20 IndustryCard). --}}
<a href="{{ route('industries.show', $industry->slug) }}" {{ $attributes->merge(['class' => 'tx-industry-card']) }}>
    <span class="tx-industry-card__icon" aria-hidden="true">
        <i class="{{ $industry->icon ?: 'fas fa-building' }}"></i>
    </span>
    <span class="tx-industry-card__name">{{ $industry->name }}</span>
    <i class="fas fa-arrow-right tx-industry-card__arrow" aria-hidden="true"></i>
</a>
