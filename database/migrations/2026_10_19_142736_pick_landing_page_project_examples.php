<?php

use App\Models\Setting;
use App\Models\Translation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Landing pages now show only the case studies picked for their service. The
 * three website projects are picked for Website Design & Development, so its
 * landing page keeps them, while Custom Software shows none until software
 * projects are picked for it. Also capitalises the one case study title that
 * was in sentence case, keeping its translations current.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private const WEBSITE_CASE_STUDIES = ['shree-vakratunda-saree', 'mangosteen-homestays', 'perfect-packaging-solution'];

    private const OLD_TITLE = 'Perfect packaging solution';

    private const NEW_TITLE = 'Perfect Packaging Solution';

    public function up(): void
    {
        $this->pickWebsiteCaseStudies();
        $this->capitaliseCaseStudyTitle();
        Setting::flushSiteCache();
    }

    /**
     * The picks and the title are content an admin may have edited since, so
     * nothing is reverted.
     */
    public function down(): void
    {
        //
    }

    /**
     * Only when nothing has been picked yet, so an admin's choice is kept.
     */
    private function pickWebsiteCaseStudies(): void
    {
        $service = DB::table('services')->where('slug', 'website-design-development')->first(['id', 'content']);

        if ($service === null) {
            return;
        }

        $content = json_decode((string) $service->content, true) ?: [];

        if (! empty($content['case_studies']['ids'])) {
            return;
        }

        $ids = DB::table('case_studies')->whereIn('slug', self::WEBSITE_CASE_STUDIES)->pluck('id', 'slug');
        $picked = array_values(array_filter(array_map(fn (string $slug): ?int => $ids[$slug] ?? null, self::WEBSITE_CASE_STUDIES)));

        if ($picked === []) {
            return;
        }

        $content['case_studies']['ids'] = $picked;

        DB::table('services')->where('id', $service->id)->update(['content' => json_encode($content), 'updated_at' => now()]);
    }

    /**
     * Only an unchanged title is renamed; translations made from it are moved
     * to the new fingerprint so they aren't flagged as outdated.
     */
    private function capitaliseCaseStudyTitle(): void
    {
        $caseStudyId = DB::table('case_studies')
            ->where('slug', 'perfect-packaging-solution')
            ->where('title', self::OLD_TITLE)
            ->value('id');

        if ($caseStudyId === null) {
            return;
        }

        DB::table('case_studies')->where('id', $caseStudyId)->update(['title' => self::NEW_TITLE, 'updated_at' => now()]);

        DB::table('translations')
            ->where('translatable_type', 'case_study')
            ->where('translatable_id', $caseStudyId)
            ->where('field', 'title')
            ->where('source_hash', Translation::hash(self::OLD_TITLE))
            ->update(['source_hash' => Translation::hash(self::NEW_TITLE)]);
    }
};
