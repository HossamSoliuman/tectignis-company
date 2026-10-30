<?php

use App\Models\Setting;
use App\Support\PortfolioCategories;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Portfolio categories become service categories (Software, SaaS, Website,
 * E-commerce…) so the public portfolio can be filtered by what was built.
 * Older marketing-phrase categories such as "A Digital Success Story" are
 * folded into the matching service category and removed.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (PortfolioCategories::NAMES as $index => $name) {
            if (! DB::table('case_study_categories')->where('name', $name)->exists()) {
                DB::table('case_study_categories')->insert([
                    'name' => $name,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $categoryIds = DB::table('case_study_categories')->pluck('id', 'name');

        $legacy = DB::table('case_study_categories')
            ->whereNotIn('name', PortfolioCategories::NAMES)
            ->get(['id', 'name']);

        foreach ($legacy as $category) {
            DB::table('case_studies')
                ->where('case_study_category_id', $category->id)
                ->update(['case_study_category_id' => $categoryIds[PortfolioCategories::match($category->name)]]);

            DB::table('translations')
                ->where('translatable_type', 'case_study_category')
                ->where('translatable_id', $category->id)
                ->delete();

            DB::table('case_study_categories')->where('id', $category->id)->delete();
        }

        Setting::flushSiteCache();
    }

    /**
     * Categories are admin-managed content from here on, so nothing is reverted.
     */
    public function down(): void
    {
        //
    }
};
