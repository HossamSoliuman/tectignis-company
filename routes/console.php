<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Keeps the cached overdue flags on portal tasks in step with their deadlines
// so dashboard alerts and reports never disagree. Requires `schedule:run` on
// cron in production.
Schedule::command('portal:refresh-overdue')->hourly()->withoutOverlapping();

// DB-IP publishes its free country database at the start of each month; the
// enquiry form reads it to preselect a visitor's calling code. Deploys run
// the same command, so the database exists even before cron first fires.
Schedule::command('ip-country:update')->monthlyOn(3, '04:00')->withoutOverlapping();
