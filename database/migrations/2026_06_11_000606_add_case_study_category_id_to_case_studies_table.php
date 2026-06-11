<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace the free-text case_studies.category column with a foreign key
     * to case_study_categories, converting existing values into categories.
     */
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->foreignId('case_study_category_id')
                ->nullable()
                ->after('title')
                ->constrained('case_study_categories')
                ->nullOnDelete();
        });

        $sortOrder = 0;

        foreach (DB::table('case_studies')->whereNotNull('category')->where('category', '!=', '')->orderBy('sort_order')->get() as $caseStudy) {
            $categoryId = DB::table('case_study_categories')->where('name', $caseStudy->category)->value('id');

            if (! $categoryId) {
                $categoryId = DB::table('case_study_categories')->insertGetId([
                    'name' => $caseStudy->category,
                    'sort_order' => $sortOrder++,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('case_studies')->where('id', $caseStudy->id)->update(['case_study_category_id' => $categoryId]);
        }

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
        });

        foreach (DB::table('case_studies')->whereNotNull('case_study_category_id')->get() as $caseStudy) {
            DB::table('case_studies')->where('id', $caseStudy->id)->update([
                'category' => DB::table('case_study_categories')->where('id', $caseStudy->case_study_category_id)->value('name'),
            ]);
        }

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('case_study_category_id');
        });
    }
};
