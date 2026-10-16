<?php

use App\Support\Locales;

if (! function_exists('localized_route')) {
    /**
     * The URL of a page in the current language. Pages without translations
     * link to their English URL.
     */
    function localized_route(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        return Locales::route($name, $parameters, $locale, $absolute);
    }
}
