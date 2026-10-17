<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Moves the language settings that change without a deploy into the
 * Languages tab: which languages are live, which service pages are
 * translated and first-visit browser detection. Every language starts in
 * preview; a Super Admin switches each one on after its check.
 */
return new class extends Migration
{
    private const KEYS = ['locales_enabled', 'locales_changes', 'locales_translated_services', 'locales_detect_browser'];

    public function up(): void
    {
        $now = now();

        DB::table('settings')->insertOrIgnore(array_map(fn (array $setting): array => [
            ...$setting,
            'group' => 'locales',
            'created_at' => $now,
            'updated_at' => $now,
        ], [
            ['key' => 'locales_enabled', 'value' => '[]'],
            ['key' => 'locales_changes', 'value' => '{}'],
            ['key' => 'locales_translated_services', 'value' => json_encode(['custom-software-development', 'website-design-development'])],
            ['key' => 'locales_detect_browser', 'value' => '1'],
        ]));

        Setting::flushSiteCache();
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', self::KEYS)->delete();

        Setting::flushSiteCache();
    }
};
