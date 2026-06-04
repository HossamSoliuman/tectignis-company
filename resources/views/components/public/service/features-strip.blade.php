@props(['service'])

@php
    $section = $service->content['features_strip'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['label'] ?? null)));
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <div class="feature-images-wrapper bg-gray section-space--ptb_60">
        <div class="container">
            <div class="row justify-content-center text-center">
                @foreach ($items as $item)
                    <div class="col-lg col-md-4 col-6 wow move-up">
                        <div class="feature-list__one text-center">
                            @if (filled($item['icon'] ?? null))
                                <div class="icon mb-15">
                                    <img class="img-fluid" src="{{ asset('uploads/'.$item['icon']) }}"
                                        alt="{{ $item['label'] }}" loading="lazy" style="max-height:48px;width:auto;display:inline-block;">
                                </div>
                            @endif
                            <h6 class="heading mb-0">{{ $item['label'] }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
