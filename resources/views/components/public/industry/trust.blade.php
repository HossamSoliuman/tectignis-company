@props(['industry'])

@php
    $section = $industry->content['trust'] ?? [];
    $brands = \App\Models\Brand::active()->ordered()->get();
@endphp

@if (($section['enabled'] ?? true) && $brands->isNotEmpty())
    <div class="ind-trust">
        <div class="container">
            <p class="ind-trust__label">Trusted by leading organizations</p>
        </div>
        <x-public.brands :brands="$brands" />
    </div>
@endif
