<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Repositions the homepage for overseas buyers of the two priority services,
 * adds the core services section headings, aligns the careers page figures
 * with the approved company statistics and corrects the phone number on the
 * legal pages to the configured company number. Admin-edited values are
 * preserved: a setting is only replaced while it still holds the seeded copy.
 */
return new class extends Migration
{
    /**
     * key => [previous seeded value, new value].
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const COPY_UPDATES = [
        'hero_sub_heading' => ['Software · AI · Cloud · Cybersecurity · IT Infrastructure', 'Custom Software · Website Development · AI · Cloud'],
        'hero_heading_line1' => ['Build, Modernize & Secure', 'Custom Software & Websites'],
        'hero_heading_line2' => ['Your Business With Technology', 'Built for Businesses Worldwide'],
        'hero_info_heading' => [
            'Custom software, AI automation, cloud infrastructure, cybersecurity and IT solutions for growing businesses and enterprises.',
            'We design and build custom software, web applications and business websites for SMEs, startups and enterprises worldwide, delivered by an in-house team with a transparent process and long-term support.',
        ],
        'hero_btn_secondary' => ['View Our Work', 'Send an Enquiry'],
        'trust_heading' => ['Trusted by businesses across India and worldwide', 'Trusted by businesses in India and internationally'],
    ];

    /**
     * New homepage settings, inserted only when missing.
     *
     * @var array<string, string>
     */
    private const NEW_SETTINGS = [
        'core_pretitle' => 'Core Services',
        'core_heading' => 'Software and Websites Built',
        'core_heading_highlight' => 'Around Your Business',
    ];

    public function up(): void
    {
        foreach (self::COPY_UPDATES as $key => [$previous, $new]) {
            DB::table('settings')->where('key', $key)->where('value', $previous)->update(['value' => $new, 'updated_at' => now()]);
        }

        foreach (self::NEW_SETTINGS as $key => $value) {
            if (! DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert(['key' => $key, 'value' => $value, 'group' => 'home', 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        $this->alignCareersStats();
        $this->correctLegalPhone();

        foreach (['site.settings', 'site.home'] as $key) {
            Cache::forget($key);
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_keys(self::NEW_SETTINGS))->delete();

        foreach (self::COPY_UPDATES as $key => [$previous, $new]) {
            DB::table('settings')->where('key', $key)->where('value', $new)->update(['value' => $previous, 'updated_at' => now()]);
        }

        foreach (['site.settings', 'site.home'] as $key) {
            Cache::forget($key);
        }
    }

    /**
     * The legal pages were seeded with a mistyped phone number; replace it with
     * the number configured in the general settings.
     */
    private function correctLegalPhone(): void
    {
        $phone = DB::table('settings')->where('key', 'site_phone')->value('value') ?: '+91 9987805688';

        DB::table('settings')
            ->where('group', 'legal')
            ->where('value', 'like', '%+91 9987705688%')
            ->get(['id', 'value'])
            ->each(fn (object $setting) => DB::table('settings')->where('id', $setting->id)->update([
                'value' => str_replace('+91 9987705688', $phone, $setting->value),
                'updated_at' => now(),
            ]));
    }

    /**
     * The careers stats strip repeats company figures (projects, clients,
     * industries) under the same labels; give those rows the approved values.
     */
    private function alignCareersStats(): void
    {
        $setting = DB::table('settings')->where('key', 'careers_stats')->first();
        $rows = json_decode((string) $setting?->value, true);

        if (! is_array($rows)) {
            return;
        }

        $approved = DB::table('stats')->where('is_active', true)->pluck('value', 'label')
            ->mapWithKeys(fn (string $value, string $label): array => [mb_strtolower($label) => $value]);

        foreach ($rows as &$row) {
            $label = mb_strtolower((string) ($row['label'] ?? ''));

            if (isset($approved[$label])) {
                $row['value'] = $approved[$label];
            }
        }

        DB::table('settings')->where('key', 'careers_stats')->update(['value' => json_encode($rows), 'updated_at' => now()]);
    }
};
