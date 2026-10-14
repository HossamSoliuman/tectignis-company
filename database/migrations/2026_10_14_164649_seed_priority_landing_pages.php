<?php

use Database\Seeders\LandingPageSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Creates the Custom Software and Website Development Google Ads landing
 * pages on existing installs. Fresh installs get them from the seeder too.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new LandingPageSeeder)->run();
    }

    public function down(): void
    {
        DB::table('landing_pages')->whereIn('slug', ['custom-software-development', 'website-development'])->delete();
    }
};
