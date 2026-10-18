@props(['items' => []])

{{--
    FAQPage JSON-LD for a list of ['question' => ..., 'answer' => ...],
    pushed to the layout's json-ld stack. Questions without an answer are left out.
--}}
@php
    $questions = collect($items)
        ->map(fn ($item): array => [
            'question' => trim(strip_tags((string) data_get($item, 'question'))),
            'answer' => trim(strip_tags((string) data_get($item, 'answer'))),
        ])
        ->filter(fn (array $item): bool => $item['question'] !== '' && $item['answer'] !== '')
        ->values();
@endphp

@if ($questions->isNotEmpty())
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions->map(fn (array $item): array => [
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
