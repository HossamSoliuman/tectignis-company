@props(['links' => []])

{{-- The page in every enabled language, as `hreflang` alternates of a sitemap entry. --}}
@foreach ($links as $hreflang => $href)
        <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}"/>
@endforeach
