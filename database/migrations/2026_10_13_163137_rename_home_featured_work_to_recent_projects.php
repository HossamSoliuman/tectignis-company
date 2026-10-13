<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Labels the home page case study section as recent projects. An admin-edited
 * label is preserved: the setting only changes while it holds the seeded copy.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->replaceBadge('Featured Work', 'Recent Projects');
    }

    public function down(): void
    {
        $this->replaceBadge('Recent Projects', 'Featured Work');
    }

    private function replaceBadge(string $from, string $to): void
    {
        DB::table('settings')
            ->where('key', 'cs_badge')
            ->where('value', $from)
            ->update(['value' => $to, 'updated_at' => now()]);

        Cache::forget('site.settings');
        Cache::forget('site.home');
    }
};
