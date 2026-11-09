<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Search engines still request URLs from the previous site, which now 404.
 * Each one with a current equivalent is sent there permanently.
 */
return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private const REDIRECTS = [
        '/services' => '/capabilities',
        '/index' => '/',
        '/index.html' => '/',
        '/contact-us' => '/contact',
        '/contact-us.php' => '/contact',
        '/careers.php' => '/careers',
        '/blog.php' => '/blog',
        '/privacy-policy' => '/legal/privacy-policy',
        '/refund-policy' => '/legal/refund-policy',
        '/refund-policy.php' => '/legal/refund-policy',
        '/expertise-it-solutions-india' => '/capabilities',
        '/expertise-it-solutions-india.php' => '/capabilities',
        '/pan-india-it-services' => '/capabilities',
        '/pan-india-it-services.php' => '/capabilities',
        '/web-application-development-navi-mumbai' => '/services/web-application-development',
        '/web-application-development-navi-mumbai.php' => '/services/web-application-development',
        '/mobile-app-development-navi-mumbai' => '/services/mobile-app-development',
        '/mobile-app-development-navi-mumbai.php' => '/services/mobile-app-development',
        '/hybrid-app-development-navi-mumbai' => '/services/mobile-app-development',
        '/web-development-in-navi-mumbai' => '/services/website-design-development',
        '/web-development-services-in-navi-mumbai' => '/services/website-design-development',
        '/website-design-development-navi-mumbai' => '/services/website-design-development',
        '/website-design-development-navi-mumbai.php' => '/services/website-design-development',
        '/custom-software-development-navi-mumbai' => '/services/custom-software-development',
        '/custom-software-development-navi-mumbai.php' => '/services/custom-software-development',
        '/network-storage-solutions-navi-mumbai-pan-india' => '/services/storage-solutions',
        '/network-storage-solutions-navi-mumbai-pan-india.php' => '/services/storage-solutions',
        '/cctv-security-solutions-navi-mumbai' => '/services/cctv-security-solutions',
        '/access-control-systems-navi-mumbai-pan-india' => '/services/access-control-systems',
        '/workstation-solutions-navi-mumbai-pan-india' => '/services/workstation-solutions',
    ];

    /**
     * Only paths with no redirect yet, so an admin's choice is kept.
     */
    public function up(): void
    {
        $now = now();

        DB::table('redirects')->insertOrIgnore(array_map(
            fn (string $from, string $to): array => [
                'from_path' => $from,
                'to_path' => $to,
                'status_code' => 301,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            array_keys(self::REDIRECTS),
            self::REDIRECTS,
        ));

        Cache::forget('site.redirects');
    }

    /**
     * The redirects are content an admin may have edited since, so nothing
     * is reverted.
     */
    public function down(): void
    {
        //
    }
};
