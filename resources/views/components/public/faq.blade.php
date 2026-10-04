@props([
    'items' => [],
    'id' => 'faq',
    'withSchema' => true,
])

{{--
    Accessible FAQ list (spec §20 FAQ). `items` is a list of
    ['question' => ..., 'answer' => ...]. Uses native <details> so it works
    without JavaScript, and emits FAQPage JSON-LD by default.
--}}
@php
    $faqItems = collect($items)
        ->map(fn ($item): array => ['question' => data_get($item, 'question'), 'answer' => data_get($item, 'answer')])
        ->filter(fn (array $item): bool => filled($item['question']) && filled($item['answer']))
        ->values();
@endphp

@if ($faqItems->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'tx-faq', 'id' => $id]) }}>
        @foreach ($faqItems as $item)
            <details class="tx-faq__item" @if ($loop->first) open @endif>
                <summary class="tx-faq__question">
                    <span>{{ $item['question'] }}</span>
                    <i class="fas fa-plus tx-faq__icon" aria-hidden="true"></i>
                </summary>
                <div class="tx-faq__answer">
                    <p>{{ $item['answer'] }}</p>
                </div>
            </details>
        @endforeach
    </div>

    @if ($withSchema)
        @php
            $faqSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqItems->map(fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                ])->all(),
            ];
        @endphp
        @push('json-ld')
            <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @endpush
    @endif
@endif
