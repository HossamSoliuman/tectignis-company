@props(['url', 'image' => null, 'topic' => null, 'date' => null, 'title', 'excerpt' => null])

<article class="res-card">
    <a href="{{ $url }}" class="res-card__media {{ $image ? '' : 'res-card__media--placeholder' }}" aria-label="{{ $title }}">
        @if ($image)
            <img src="{{ asset('uploads/'.$image) }}" alt="{{ $title }}" loading="lazy">
        @else
            <i class="far fa-image" aria-hidden="true"></i>
        @endif
    </a>
    <div class="res-card__body">
        <div class="res-card__meta">
            @php
                $displayDate = $date?->timezone(config('app.timezone'));
            @endphp
            @if ($topic)
                <span class="res-card__topic">{{ $topic }}</span>
            @endif
            @if ($displayDate)
                <span class="res-card__date"><i class="far fa-calendar" aria-hidden="true"></i> {{ $displayDate->format('M d, Y H:i \G\M\T P') }}</span>
            @endif
        </div>
        <h3 class="res-card__title"><a href="{{ $url }}">{{ $title }}</a></h3>
        @if ($excerpt)
            <p class="res-card__excerpt">{{ $excerpt }}</p>
        @endif
        <a href="{{ $url }}" class="res-card__more">Read More <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</article>
