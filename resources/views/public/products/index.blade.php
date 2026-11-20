@extends('layouts.public')

@section('title', __('products.title'))

@section('seo')
    <meta name="description" content="{{ __('products.description') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="__('nav.products')" :items="[__('nav.products') => null]" />
@endsection

@section('content')
    <section class="tx-section tx-section--dark">
        <div class="container">
            <x-public.section-heading
                tone="dark"
                :pretitle="__('home.products_pretitle')"
                :title="__('home.products_heading')"
                :highlight="__('home.products_highlight')"
            />
            @if ($products->isEmpty())
                <div class="res-empty">
                    <i class="fas fa-cube" aria-hidden="true"></i>
                    <p class="mb-0">{{ __('products.empty') }}</p>
                </div>
            @else
                <div class="tx-grid tx-grid--3">
                    @foreach ($products as $product)
                        <x-public.product-card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-public.cta />
@endsection
