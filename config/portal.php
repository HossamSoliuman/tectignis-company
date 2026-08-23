<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Document Storage Disk
    |--------------------------------------------------------------------------
    |
    | Where confidential portal documents are written. Must always be a private
    | disk — nothing here is safe to expose over the web. Point this at "s3" to
    | move the whole module to cloud storage without touching any code.
    |
    */

    'disk' => env('PORTAL_FILESYSTEM_DISK', 'portal'),

    /*
    |--------------------------------------------------------------------------
    | Deadline Horizons
    |--------------------------------------------------------------------------
    |
    | How far ahead "due soon" reaches, in days, on the dashboard and My Work.
    |
    */

    'due_soon_days' => env('PORTAL_DUE_SOON_DAYS', 3),

    /*
    |--------------------------------------------------------------------------
    | Tender Deadline Horizon
    |--------------------------------------------------------------------------
    |
    | How many days ahead a submission deadline counts as "closing soon" on the
    | dashboard and the tender index. Bid preparation needs more warning than a
    | day-to-day task, hence the separate horizon.
    |
    */

    'tender_closing_soon_days' => env('PORTAL_TENDER_CLOSING_SOON_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | List Pagination
    |--------------------------------------------------------------------------
    */

    'per_page' => env('PORTAL_PER_PAGE', 20),

];
