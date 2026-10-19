<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Until software case studies are added, the Custom Software landing page
 * shows the same three projects as the website page. Picking software
 * projects for the service in the admin replaces them.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private const INTERIM_CASE_STUDIES = ['shree-vakratunda-saree', 'mangosteen-homestays', 'perfect-packaging-solution'];

    /**
     * Only when nothing has been picked yet, so an admin's choice is kept.
     */
    public function up(): void
    {
        $service = DB::table('services')->where('slug', 'custom-software-development')->first(['id', 'content']);

        if ($service === null) {
            return;
        }

        $content = json_decode((string) $service->content, true) ?: [];

        if (! empty($content['case_studies']['ids'])) {
            return;
        }

        $ids = DB::table('case_studies')->whereIn('slug', self::INTERIM_CASE_STUDIES)->pluck('id', 'slug');
        $picked = array_values(array_filter(array_map(fn (string $slug): ?int => $ids[$slug] ?? null, self::INTERIM_CASE_STUDIES)));

        if ($picked === []) {
            return;
        }

        $content['case_studies']['ids'] = $picked;

        DB::table('services')->where('id', $service->id)->update(['content' => json_encode($content), 'updated_at' => now()]);
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
