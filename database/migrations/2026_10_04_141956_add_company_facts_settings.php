<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Moves company registration details out of the footer template into
 * admin-editable settings so all company facts live in one place (spec §20).
 */
return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private const SETTINGS = [
        'company_legal_name' => 'Tectignis IT Solutions Pvt. Ltd.',
        'company_gstin' => '27AAICT1840Q1ZS',
        'company_cin' => 'U72900MH2020PTC348594',
    ];

    public function up(): void
    {
        foreach (self::SETTINGS as $key => $value) {
            if (! DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'group' => 'general',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_keys(self::SETTINGS))->delete();
    }
};
