<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Applies the homepage redesign copy (spec §4.2, §4.3, §11.1). Admin-edited
 * values are preserved: a setting is only replaced while it still holds the
 * previously seeded copy, and the process steps are only replaced while they
 * are still the original five.
 */
return new class extends Migration
{
    /**
     * key => [previous seeded values, new value].
     *
     * @var array<string, array{0: list<string|null>, 1: string}>
     */
    private const COPY_UPDATES = [
        'hero_sub_heading' => [['Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.'], 'Software · AI · Cloud · Cybersecurity · IT Infrastructure'],
        'hero_heading_line1' => [['Transforming Businesses Through'], 'Build, Modernize & Secure'],
        'hero_heading_line2' => [['Software, AI & Smart Technology Solutions'], 'Your Business With Technology'],
        'hero_info_heading' => [[
            'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems.',
            'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems',
        ], 'Custom software, AI automation, cloud infrastructure, cybersecurity and IT solutions for growing businesses and enterprises.'],
        'hero_btn_primary' => [['Request Consultation'], 'Book a Technical Consultation'],
        'hero_btn_secondary' => [['Get a Quote'], 'View Our Work'],
        'cta_btn_primary' => [['Request Consultation'], 'Book a Technical Consultation'],
        'cta_btn_secondary' => [['Get a Quote'], 'Request a Project Assessment'],
        'cs_badge' => [['CASE STUDIES'], 'Featured Work'],
    ];

    /**
     * New homepage settings, inserted only when missing.
     *
     * @var array<string, string>
     */
    private const NEW_SETTINGS = [
        'trust_heading' => 'Trusted by businesses across India and worldwide',
        'pillars_pretitle' => 'What We Do',
        'pillars_heading' => 'Four Pillars. One Accountable Technology Partner.',
        'pillars_subtitle' => 'Tectignis helps businesses build, modernize and secure their technology through custom software, AI automation, cloud, cybersecurity and IT infrastructure solutions.',
        'process_pretitle' => 'How We Work',
        'process_heading' => 'A Delivery Process You Can Plan Around',
        'process_subtitle' => 'Every engagement follows the same accountable path, from first discovery call to long-term support.',
    ];

    /**
     * @var list<string>
     */
    private const ORIGINAL_STEPS = ['Understand', 'Strategize', 'Develop', 'Deliver', 'Grow Together'];

    /**
     * @var list<array{title: string, description: string, icon: string}>
     */
    private const PROCESS_STEPS = [
        ['title' => 'Discovery', 'description' => 'Business goals, users, constraints and success criteria.', 'icon' => '<path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>'],
        ['title' => 'Architecture', 'description' => 'Solution design, integrations, security and hosting plan.', 'icon' => '<path d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>'],
        ['title' => 'Design', 'description' => 'UX flows, interface design and clickable prototypes.', 'icon' => '<path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>'],
        ['title' => 'Build', 'description' => 'Iterative development with regular demos and reviews.', 'icon' => '<path d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>'],
        ['title' => 'QA', 'description' => 'Functional, performance and security testing before release.', 'icon' => '<path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>'],
        ['title' => 'Deploy', 'description' => 'Controlled go-live, data migration and handover.', 'icon' => '<path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>'],
        ['title' => 'Support', 'description' => 'Monitoring, maintenance, AMC and continuous improvement.', 'icon' => '<path d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/>'],
    ];

    public function up(): void
    {
        foreach (self::COPY_UPDATES as $key => [$previousValues, $newValue]) {
            $current = DB::table('settings')->where('key', $key)->first();

            if (! $current) {
                DB::table('settings')->insert(['key' => $key, 'value' => $newValue, 'group' => 'home', 'created_at' => now(), 'updated_at' => now()]);
            } elseif ($current->value === null || in_array($current->value, $previousValues, true)) {
                DB::table('settings')->where('key', $key)->update(['value' => $newValue, 'updated_at' => now()]);
            }
        }

        foreach (self::NEW_SETTINGS as $key => $value) {
            if (! DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert(['key' => $key, 'value' => $value, 'group' => 'home', 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        $this->replaceOriginalProcessSteps();
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_keys(self::NEW_SETTINGS))->delete();

        foreach (self::COPY_UPDATES as $key => [$previousValues, $newValue]) {
            DB::table('settings')->where('key', $key)->where('value', $newValue)->update(['value' => $previousValues[0]]);
        }
    }

    private function replaceOriginalProcessSteps(): void
    {
        $titles = DB::table('process_steps')->orderBy('sort_order')->pluck('title')->all();

        if ($titles !== [] && $titles !== self::ORIGINAL_STEPS) {
            return;
        }

        DB::table('process_steps')->delete();

        foreach (self::PROCESS_STEPS as $index => $step) {
            DB::table('process_steps')->insert($step + [
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
