{{--
    Language switcher: keeps the visitor on the same page in the chosen
    language, or sends them to that language's homepage from a page that has
    no translations. Renders nothing while English is the only language the
    viewer can reach; `in-list-item` wraps it in an <li> only when it renders.
--}}
@props(['variant' => 'dropdown', 'inListItem' => false])

@php $languageLinks = \App\Support\Locales::switcherLinks(auth()->user()); @endphp

@if ($languageLinks !== [])
    @if ($variant === 'list')
        <ul {{ $attributes->class('tx-lang-list') }} aria-label="{{ __('common.language') }}">
            @foreach ($languageLinks as $code => $link)
                <li>
                    <a href="{{ $link['url'] }}" hreflang="{{ $code }}" lang="{{ $code }}" @if ($link['current']) aria-current="true" @endif>{{ $link['name'] }}</a>
                </li>
            @endforeach
        </ul>
    @else
        @if ($inListItem)
            <li>
        @endif
        <details {{ $attributes->class('tx-lang') }}>
            <summary class="tx-lang__toggle" aria-label="{{ __('common.language') }}">
                <i class="fas fa-globe" aria-hidden="true"></i>
                <span>{{ $languageLinks[\App\Support\Locales::current()]['name'] ?? \App\Support\Locales::name(\App\Support\Locales::current()) }}</span>
            </summary>
            <ul class="tx-lang__menu">
                @foreach ($languageLinks as $code => $link)
                    <li>
                        <a href="{{ $link['url'] }}" hreflang="{{ $code }}" lang="{{ $code }}" @if ($link['current']) aria-current="true" @endif>{{ $link['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </details>
        @if ($inListItem)
            </li>
        @endif
    @endif
@endif
