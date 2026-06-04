@props(['service'])

@php
    $section = $service->content['sub_services'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['title'] ?? null)));
    $heading = $section['heading'] ?? 'Our '.$service->title.' Services';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        @if (filled($subtitle))
                            <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                        @endif
                        <h3 class="heading">{{ $heading }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($items as $item)
                    <div class="col-lg-4 col-md-6 wow move-up section-space--mt_30">
                        <div class="ht-box-icon style-01 h-100">
                            <div class="icon-box-wrap">
                                @if (filled($item['icon'] ?? null))
                                    <div class="icon mb-20">
                                        <img class="img-fluid" src="{{ asset('uploads/'.$item['icon']) }}"
                                            alt="{{ $item['title'] }}" loading="lazy" style="max-height:56px;width:auto;">
                                    </div>
                                @endif
                                <div class="content">
                                    <h5 class="heading">{{ $item['title'] }}</h5>
                                    @if (filled($item['description'] ?? null))
                                        <p class="text mt-10">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
