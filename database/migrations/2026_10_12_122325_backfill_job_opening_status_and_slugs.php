<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Carries existing openings over to the new posting model: inactive openings
 * become drafts, active ones are published as of their creation date, and
 * every opening gets a unique slug for its public page.
 */
return new class extends Migration
{
    public function up(): void
    {
        $taken = [];

        DB::table('job_openings')->orderBy('id')->get(['id', 'title', 'is_active', 'created_at'])
            ->each(function (object $opening) use (&$taken): void {
                $base = Str::slug(str_replace('/', ' ', $opening->title)) ?: 'job';
                $slug = $base;

                for ($suffix = 2; in_array($slug, $taken, true); $suffix++) {
                    $slug = $base.'-'.$suffix;
                }

                $taken[] = $slug;

                DB::table('job_openings')->where('id', $opening->id)->update([
                    'slug' => $slug,
                    'status' => $opening->is_active ? 'published' : 'draft',
                    'published_at' => $opening->is_active ? ($opening->created_at ?? now()) : null,
                ]);
            });
    }

    public function down(): void
    {
        DB::table('job_openings')->update(['is_active' => DB::raw("CASE WHEN status = 'published' THEN 1 ELSE 0 END")]);
    }
};
