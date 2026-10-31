<?php

use App\Models\Setting;
use Database\Seeders\LandingPageSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the E-commerce Google Ads landing page on existing installs and picks
 * its project examples: the two e-commerce projects and the retail campaign
 * microsite. The seeder only creates missing pages, so the other pages keep
 * their admin edits, and picks an admin already made are kept.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private const PICKS = ['shree-vakratunda-saree', 'velo-corporate-gifting-catalogue', 'diva-junction-big-billion-days-microsite'];

    public function up(): void
    {
        (new LandingPageSeeder)->run();

        $page = DB::table('landing_pages')->where('slug', 'ecommerce-development')->first(['id', 'content']);
        $content = json_decode((string) $page?->content, true) ?: [];

        if ($page !== null && empty($content['case_studies']['ids'])) {
            $ids = DB::table('case_studies')->where('status', 'published')->pluck('id', 'slug');
            $picked = array_values(array_filter(array_map(fn (string $slug): ?int => $ids[$slug] ?? null, self::PICKS)));

            if ($picked !== []) {
                $content['case_studies']['ids'] = $picked;

                DB::table('landing_pages')->where('id', $page->id)->update(['content' => json_encode($content), 'updated_at' => now()]);
            }
        }

        Setting::flushSiteCache();
    }

    public function down(): void
    {
        DB::table('landing_pages')->where('slug', 'ecommerce-development')->delete();
    }
};
