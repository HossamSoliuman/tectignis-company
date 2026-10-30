<?php

use App\Models\Setting;
use Database\Seeders\PortfolioProjectSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the live and in-progress client and product projects to the
 * portfolio on existing installs. The seeder skips any slug that already
 * exists, so projects edited in the admin keep their changes.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private const SLUGS = [
        'tenderset-government-tender-platform',
        'constituency-management-system',
        'ganeshotsav-toll-free-pass-portal',
        'alignocare-dental-clinic-website',
        'back-to-campus-aura-agreement-microsite',
        'ilccpl-liquid-bulk-chartering-website',
        'tectignis-qr-code-generator',
        'velo-corporate-gifting-catalogue',
        'waterfirst-engineering-consultancy-website',
        'diva-junction-big-billion-days-microsite',
        'teachers-open-comedy-festival-microsite',
        'society-management-saas-platform',
        'shree-sudarshan-water-conditioner-website',
    ];

    public function up(): void
    {
        (new PortfolioProjectSeeder)->run();
        Setting::flushSiteCache();
    }

    public function down(): void
    {
        DB::table('case_studies')->whereIn('slug', self::SLUGS)->delete();
        Setting::flushSiteCache();
    }
};
