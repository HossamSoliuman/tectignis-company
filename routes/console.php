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
