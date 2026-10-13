<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * The home page lays global advantages out four to a row, and live installs
 * had five, leaving a lone card on the second row. These three fill it.
 * Existing titles are left untouched so admin edits survive.
 */
return new class extends Migration
{
    /**
     * @var list<array{title: string, description: string, tone: string, icon: string}>
     */
    private array $advantages = [
        [
            'title' => 'Transparent Pricing',
            'description' => 'Clear estimates and milestone-based billing with no hidden costs.',
            'tone' => 'indigo',
            'icon' => '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M10 13h6M10 17h6"/>',
        ],
        [
            'title' => 'Dedicated Project Manager',
            'description' => 'A single point of contact keeping every milestone on schedule.',
            'tone' => 'rose',
            'icon' => '<circle cx="9" cy="8" r="4"/><path d="M2 21a7 7 0 0 1 14 0M16 11l2 2 4-4"/>',
        ],
        [
            'title' => 'Long-Term Partnership',
            'description' => 'Ongoing maintenance, upgrades and support well beyond launch.',
            'tone' => 'indigo',
            'icon' => '<path d="M21 12a9 9 0 0 1-15.5 6.2L3 16M3 12a9 9 0 0 1 15.5-6.2L21 8M21 3v5h-5M3 21v-5h5"/>',
        ],
    ];

    public function up(): void
    {
        $sortOrder = (int) DB::table('global_advantages')->max('sort_order');

        foreach ($this->advantages as $advantage) {
            if (DB::table('global_advantages')->where('title', $advantage['title'])->exists()) {
                continue;
            }

            DB::table('global_advantages')->insert(array_merge($advantage, [
                'sort_order' => ++$sortOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        Cache::forget('site.home');
    }

    public function down(): void
    {
        DB::table('global_advantages')
            ->whereIn('title', array_column($this->advantages, 'title'))
            ->delete();

        Cache::forget('site.home');
    }
};
