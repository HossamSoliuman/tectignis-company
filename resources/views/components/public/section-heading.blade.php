@props([
    'pretitle' => null,
    'title',
    'highlight' => null,
    'subtitle' => null,
    'align' => 'center',
    'tone' => 'light',
])

<div {{ $attributes->merge(['class' => 'tx-heading tx-heading--'.$align.' tx-heading--'.$tone]) }}>
    @if ($pretitle)
        <span class="tx-heading__pretitle">{{ $pretitle }}</span>
    @endif
    <h2 class="tx-heading__title">
        {{ $title }}
        @if ($highlight)
            <span class="tx-heading__highlight">{{ $highlight }}</span>
        @endif
    </h2>
    @if ($subtitle)
        <p class="tx-heading__subtitle">{{ $subtitle }}</p>
    @endif
</div>
