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
                    <a href="{{ $link['url'] }}" hreflang="{{ $code }}" lang="{{ $code }}" dir="{{ \App\Support\Locales::direction($code) }}" @if ($link['current']) aria-current="true" @endif>{{ $link['name'] }}</a>
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
                        <a href="{{ $link['url'] }}" hreflang="{{ $code }}" lang="{{ $code }}" dir="{{ \App\Support\Locales::direction($code) }}" @if ($link['current']) aria-current="true" @endif>{{ $link['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </details>
        @if ($inListItem)
            </li>
        @endif

        @once
        @push('scripts')
        <script>
            (function () {
                // A native <details> only closes from its own toggle; also close the
                // menu on a click anywhere outside it, and on Escape.
                function closeLanguageMenus(except) {
                    document.querySelectorAll('details.tx-lang[open]').forEach(function (menu) {
                        if (menu !== except) {
                            menu.removeAttribute('open');
                        }
                    });
                }

                document.addEventListener('click', function (event) {
                    closeLanguageMenus(event.target.closest('details.tx-lang'));
                });

                document.addEventListener('keydown', function (event) {
                    var open = document.querySelector('details.tx-lang[open]');

                    if (event.key === 'Escape' && open) {
                        closeLanguageMenus(null);
                        open.querySelector('summary').focus();
                    }
                });
            })();
        </script>
        @endpush
        @endonce
    @endif
@endif
