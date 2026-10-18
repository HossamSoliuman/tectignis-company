<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Settings screen showed robots.txt in a single-line field, so saving it
 * joined its directives into one line ("User-agent: *Allow: /"), which
 * crawlers can't read. Every directive goes back on its own line.
 */
return new class extends Migration
{
    public function up(): void
    {
        $robots = DB::table('settings')->where('key', 'robots_txt')->value('value');

        if (! is_string($robots)) {
            return;
        }

        $repaired = preg_replace('/(?<=\S)[ \t]*\b(?=(?:User-agent|Allow|Disallow|Sitemap|Crawl-delay)\s*:)/i', "\n", $robots);

        if ($repaired !== $robots) {
            DB::table('settings')->where('key', 'robots_txt')->update(['value' => $repaired, 'updated_at' => now()]);
            Setting::flushSiteCache();
        }
    }

    /**
     * The joined lines were never valid, so there is nothing to restore.
     */
    public function down(): void
    {
        //
    }
};
