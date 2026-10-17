<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
     * Finding the visitor's country to record it on their lead, preselect
     * the enquiry form's calling code and pick their language on a first
     * visit (config/locales.php lists each country's language). A CDN's
     * country header wins when the site is behind one (Cloudflare sends
     * CF-IPCountry); otherwise the IP
     * address is looked up in the DB-IP country database that
     * `php artisan ip-country:update` keeps in storage.
     */
    'country_detection' => [
        'header' => env('COUNTRY_DETECTION_HEADER', 'CF-IPCountry'),
        'database' => storage_path('app/geoip/dbip-country-lite.mmdb'),
    ],

];
