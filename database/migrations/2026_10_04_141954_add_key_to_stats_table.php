<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Gives each company metric a stable key so every template reads the same
 * approved figure from the stats table (spec §10.1, §20).
 */
return new class extends Migration
{
    /**
     * Label fragments used to backfill keys on existing rows.
     *
     * @var array<string, string>
     */
    private const KEY_PATTERNS = [
        'projects' => '%project%',
        'clients' => '%client%',
        'industries' => '%industr%',
        'years' => '%year%',
        'countries' => '%countr%',
        'support' => '%support%',
    ];

    public function up(): void
    {
        Schema::table('stats', function (Blueprint $table) {
            $table->string('key')->nullable()->unique()->after('id');
        });

        foreach (self::KEY_PATTERNS as $key => $pattern) {
            $id = DB::table('stats')->whereNull('key')->where('label', 'like', $pattern)->orderBy('sort_order')->value('id');

            if ($id) {
                DB::table('stats')->where('id', $id)->update(['key' => $key]);
            }
        }

        if (! DB::table('stats')->where('key', 'countries')->exists()) {
            DB::table('stats')->insert([
                'key' => 'countries',
                'value' => '25+',
                'label' => 'Countries Served',
                'sort_order' => (int) DB::table('stats')->max('sort_order') + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('stats', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->dropColumn('key');
        });
    }
};
