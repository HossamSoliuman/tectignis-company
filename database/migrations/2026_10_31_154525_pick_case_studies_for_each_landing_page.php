<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Landing pages now pick their own project examples. Each Google Ads page
 * gets the portfolio projects closest to what it sells; IT Infrastructure,
 * with no networking projects in the portfolio yet, shows the platforms
 * Tectignis hosts and runs.
 */
return new class extends Migration
{
    /**
     * @var array<string, list<string>>
     */
    private const PICKS = [
        'custom-software-development' => ['constituency-management-system', 'ganeshotsav-toll-free-pass-portal', 'tenderset-government-tender-platform'],
        'website-development' => ['alignocare-dental-clinic-website', 'ilccpl-liquid-bulk-chartering-website', 'shree-sudarshan-water-conditioner-website'],
        'erp-crm-development' => ['constituency-management-system', 'society-management-saas-platform', 'tenderset-government-tender-platform'],
        'saas-development' => ['tenderset-government-tender-platform', 'society-management-saas-platform', 'tectignis-qr-code-generator'],
        'ai-automation' => ['tenderset-government-tender-platform', 'ganeshotsav-toll-free-pass-portal', 'constituency-management-system'],
        'it-infrastructure' => ['tenderset-government-tender-platform', 'society-management-saas-platform', 'ganeshotsav-toll-free-pass-portal'],
    ];

    /**
     * Only pages with nothing picked yet, so an admin's choice is kept.
     */
    public function up(): void
    {
        $ids = DB::table('case_studies')->where('status', 'published')->pluck('id', 'slug');

        foreach (self::PICKS as $pageSlug => $caseStudySlugs) {
            $page = DB::table('landing_pages')->where('slug', $pageSlug)->first(['id', 'content']);

            if ($page === null) {
                continue;
            }

            $content = json_decode((string) $page->content, true) ?: [];

            if (! empty($content['case_studies']['ids'])) {
                continue;
            }

            $picked = array_values(array_filter(array_map(fn (string $slug): ?int => $ids[$slug] ?? null, $caseStudySlugs)));

            if ($picked === []) {
                continue;
            }

            $content['case_studies']['ids'] = $picked;

            DB::table('landing_pages')->where('id', $page->id)->update(['content' => json_encode($content), 'updated_at' => now()]);
        }

        Setting::flushSiteCache();
    }

    /**
     * The picks are content an admin may have edited since, so nothing is
     * reverted.
     */
    public function down(): void
    {
        //
    }
};
