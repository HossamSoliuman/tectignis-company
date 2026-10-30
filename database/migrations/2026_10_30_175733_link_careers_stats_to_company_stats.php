<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The careers stats strip held typed-in copies of the company figures
 * (projects, clients, industries…). Link those rows to the approved
 * statistics instead, keeping their labels, so the careers page always shows
 * the same figure as the rest of the site and drops it when it is switched off.
 */
return new class extends Migration
{
    /**
     * Label fragments that identify a company statistic, by stat key.
     *
     * @var array<string, string>
     */
    private const KEY_PATTERNS = [
        'projects' => 'project',
        'clients' => 'client',
        'industries' => 'industr',
        'countries' => 'countr',
        'years' => 'year',
        'support' => 'support',
    ];

    public function up(): void
    {
        $setting = DB::table('settings')->where('key', 'careers_stats')->first();
        $rows = json_decode((string) $setting?->value, true);

        if (! is_array($rows)) {
            return;
        }

        $keys = DB::table('stats')->whereNotNull('key')->pluck('key')->all();

        foreach ($rows as &$row) {
            $label = mb_strtolower((string) ($row['label'] ?? ''));

            foreach (self::KEY_PATTERNS as $key => $fragment) {
                if (in_array($key, $keys, true) && str_contains($label, $fragment)) {
                    $row = array_filter([
                        'icon' => $row['icon'] ?? null,
                        'stat' => $key,
                        'label' => $row['label'] ?? null,
                    ]);

                    break;
                }
            }
        }

        DB::table('settings')->where('key', 'careers_stats')->update(['value' => json_encode($rows), 'updated_at' => now()]);
        Setting::flushSiteCache();
    }

    /**
     * Linked rows still render; the careers editor can turn them back into
     * custom values, so nothing is reverted.
     */
    public function down(): void
    {
        //
    }
};
