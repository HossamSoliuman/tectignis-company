@props(['industry'])

@php
    $ch = $industry->content['challenges'] ?? [];
    $sol = $industry->content['solutions'] ?? [];

    $challenges = array_values(array_filter($ch['items'] ?? [], fn ($i) => filled($i)));
    $cards = array_values(array_filter($sol['cards'] ?? [], fn ($c) => filled($c['title'] ?? null)));

    $showChallenges = ($ch['enabled'] ?? true) && count($challenges);
    $showSolutions = ($sol['enabled'] ?? true) && count($cards);
@endphp

@if ($showChallenges || $showSolutions)
    <section class="svc-section ind-split">
        <div class="container">
            <div class="row">
                @if ($showChallenges)
                    <div class="col-lg-5 col-md-12 ind-split__left wow move-up">
                        @if (filled($ch['eyebrow'] ?? null))
                            <span class="svc-eyebrow">{{ $ch['eyebrow'] }}</span>
                        @endif
                        <h2 class="svc-section-title ind-split__title">{{ $ch['heading'] ?? 'New-Age Challenges.' }}</h2>
                        @if (filled($ch['subtitle'] ?? null))
                            <p class="ind-split__intro">{{ $ch['subtitle'] }}</p>
                        @endif
                        <ul class="ind-challenges">
                            @foreach ($challenges as $challenge)
                                <li>
                                    <span class="ind-challenges__icon"><i class="fas fa-check"></i></span>
                                    <span>{{ $challenge }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($showSolutions)
                    <div class="{{ $showChallenges ? 'col-lg-7' : 'col-lg-12' }} col-md-12 ind-split__right wow move-up">
                        @if (filled($sol['eyebrow'] ?? null))
                            <span class="svc-eyebrow">{{ $sol['eyebrow'] }}</span>
                        @endif
                        @if (filled($sol['heading'] ?? null))
                            <h2 class="svc-section-title ind-split__title">{{ $sol['heading'] }}</h2>
                        @endif
                        <div class="row ind-solutions__grid">
                            @foreach ($cards as $card)
                                <div class="col-md-6 col-12">
                                    <div class="ind-solution-card">
                                        <span class="ind-solution-card__icon">
                                            @if (filled($card['icon'] ?? null))
                                                <img src="{{ asset('uploads/'.$card['icon']) }}" alt="" loading="lazy">
                                            @else
                                                <i class="fas fa-shield-alt"></i>
                                            @endif
                                        </span>
                                        <h3 class="ind-solution-card__title">{{ $card['title'] }}</h3>
                                        @if (filled($card['description'] ?? null))
                                            <p class="ind-solution-card__text">{{ $card['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
