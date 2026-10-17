<?php

/*
|--------------------------------------------------------------------------
| Languages
|--------------------------------------------------------------------------
|
| Every language the public site can be served in. English is the default
| and keeps today's unprefixed URLs; every other language lives under its
| own prefix (/ar/, /es/, ...). A language is only served to visitors once
| a Super Admin switches it on in Settings → Languages. Until then it
| returns 404, except to CMS users, who can preview it.
|
*/

return [

    'default' => 'en',

    'supported' => [
        'en' => ['label' => 'English', 'name' => 'English', 'dir' => 'ltr', 'og_locale' => 'en_US'],
        'ar' => ['label' => 'Arabic', 'name' => 'العربية', 'dir' => 'rtl', 'og_locale' => 'ar_AE'],
        'es' => ['label' => 'Spanish', 'name' => 'Español', 'dir' => 'ltr', 'og_locale' => 'es_ES'],
        'fr' => ['label' => 'French', 'name' => 'Français', 'dir' => 'ltr', 'og_locale' => 'fr_FR'],
        'de' => ['label' => 'German', 'name' => 'Deutsch', 'dir' => 'ltr', 'og_locale' => 'de_DE'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translated pages
    |--------------------------------------------------------------------------
    |
    | Route names that have a version in every language. A route listed with
    | slugs is translated for those slugs only; any other slug stays English.
    | The translated service pages are chosen in Settings → Languages.
    |
    */

    'translated_pages' => [
        'home' => null,
        'landing-pages.show' => null,
        'contact' => null,
        'contact.submit' => null,
        'contact.thank-you' => null,
        'legal.show' => ['privacy-policy', 'terms-and-conditions'],
    ],

];
