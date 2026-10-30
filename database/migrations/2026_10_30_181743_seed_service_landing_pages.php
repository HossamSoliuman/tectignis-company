<?php

use App\Models\Setting;
use Database\Seeders\LandingPageSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the ERP & CRM, SaaS, AI & Business Automation and IT Infrastructure
 * Google Ads landing pages on existing installs. The seeder only creates
 * missing pages, so the two existing ones keep their admin edits.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new LandingPageSeeder)->run();
        Setting::flushSiteCache();
    }

    public function down(): void
    {
        DB::table('landing_pages')->whereIn('slug', ['erp-crm-development', 'saas-development', 'ai-automation', 'it-infrastructure'])->delete();
    }
};
