<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Turns case studies into full portfolio records: client, industry, service,
 * location, delivery stage, the challenge/solution story, modules, stack,
 * screenshots, verified outcome, testimonial, project date, featured flag and
 * SEO fields, with a draft/published/archived workflow replacing is_active.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('status', 20)->default('draft')->after('slug')->index();
            $table->string('project_status', 20)->default('completed')->after('status');
            $table->string('client_name')->nullable()->after('title');
            $table->foreignId('industry_id')->nullable()->after('case_study_category_id')->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->after('industry_id')->constrained()->nullOnDelete();
            $table->string('location')->nullable()->after('service_id');
            $table->date('project_date')->nullable()->after('location');
            $table->text('challenge')->nullable()->after('short_description');
            $table->text('solution')->nullable()->after('challenge');
            $table->json('modules')->nullable()->after('solution');
            $table->json('technologies')->nullable()->after('modules');
            $table->text('outcome')->nullable()->after('technologies');
            $table->string('image_alt')->nullable()->after('image');
            $table->json('gallery')->nullable()->after('image_alt');
            $table->foreignId('testimonial_id')->nullable()->after('gallery')->constrained()->nullOnDelete();
            $table->boolean('is_featured')->default(false)->after('sort_order');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
        });

        DB::table('case_studies')->update([
            'status' => DB::raw("CASE WHEN is_active = 1 THEN 'published' ELSE 'draft' END"),
        ]);

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sort_order');
        });

        DB::table('case_studies')->update([
            'is_active' => DB::raw("CASE WHEN status = 'published' THEN 1 ELSE 0 END"),
        ]);

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('industry_id');
            $table->dropConstrainedForeignId('service_id');
            $table->dropConstrainedForeignId('testimonial_id');
            $table->dropIndex(['status']);
            $table->dropColumn([
                'status', 'project_status', 'client_name', 'location', 'project_date',
                'challenge', 'solution', 'modules', 'technologies', 'outcome', 'image_alt',
                'gallery', 'is_featured', 'seo_title', 'seo_description',
            ]);
        });
    }
};
