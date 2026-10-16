<?php

/*
|--------------------------------------------------------------------------
| Languages
|--------------------------------------------------------------------------
|
| Every language the public site can be served in. English is the default
| and keeps today's unprefixed URLs; every other language lives under its
| own prefix (/ar/, /es/, ...). A language is only served to visitors once
| its code is listed in LOCALES_ENABLED, e.g. LOCALES_ENABLED=ar,es. Until
| then it returns 404, except to CMS users, who can preview it. `script` is
| the ISO 15924 code of the language's writing system, which picks its fonts.
|
*/

$enabled = array_filter(array_map('trim', explode(',', (string) env('LOCALES_ENABLED', ''))));

return [

    'default' => 'en',

    'supported' => [
        'en' => ['label' => 'English', 'name' => 'English', 'dir' => 'ltr', 'script' => 'Latn', 'og_locale' => 'en_US', 'enabled' => true],
        'ar' => ['label' => 'Arabic', 'name' => 'العربية', 'dir' => 'rtl', 'script' => 'Arab', 'og_locale' => 'ar_AE', 'enabled' => in_array('ar', $enabled, true)],
        'es' => ['label' => 'Spanish', 'name' => 'Español', 'dir' => 'ltr', 'script' => 'Latn', 'og_locale' => 'es_ES', 'enabled' => in_array('es', $enabled, true)],
        'fr' => ['label' => 'French', 'name' => 'Français', 'dir' => 'ltr', 'script' => 'Latn', 'og_locale' => 'fr_FR', 'enabled' => in_array('fr', $enabled, true)],
        'de' => ['label' => 'German', 'name' => 'Deutsch', 'dir' => 'ltr', 'script' => 'Latn', 'og_locale' => 'de_DE', 'enabled' => in_array('de', $enabled, true)],
        'hi' => ['label' => 'Hindi', 'name' => 'हिन्दी', 'dir' => 'ltr', 'script' => 'Deva', 'og_locale' => 'hi_IN', 'enabled' => in_array('hi', $enabled, true)],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translated pages
    |--------------------------------------------------------------------------
    |
    | Route names that have a version in every language. A route listed with
    | slugs is translated for those slugs only; any other slug stays English.
    |
    */

    'translated_pages' => [
        'home' => null,
        'services.show' => ['custom-software-development', 'website-design-development'],
        'landing-pages.show' => null,
        'contact' => null,
        'contact.submit' => null,
        'contact.thank-you' => null,
        'legal.show' => ['privacy-policy', 'terms-and-conditions'],
    ],

];
