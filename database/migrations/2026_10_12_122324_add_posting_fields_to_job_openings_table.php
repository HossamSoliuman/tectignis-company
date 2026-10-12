<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns job openings into full job postings with their own public page
 * (spec §26.9): slug, posting content, salary, deadline, a Draft/Published/
 * Closed status and SEO overrides.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('experience')->nullable()->after('employment_type');
            $table->text('skills')->nullable()->after('experience');
            $table->longText('description')->nullable()->after('skills');
            $table->longText('responsibilities')->nullable()->after('description');
            $table->longText('requirements')->nullable()->after('responsibilities');
            $table->longText('benefits')->nullable()->after('requirements');
            $table->string('salary')->nullable()->after('benefits');
            $table->boolean('show_salary')->default(false)->after('salary');
            $table->date('application_deadline')->nullable()->after('show_salary');
            $table->string('status')->default('draft')->after('application_deadline')->index();
            $table->timestamp('published_at')->nullable()->after('status');
            $table->string('seo_title')->nullable()->after('published_at');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
        });
    }

    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'slug', 'experience', 'skills', 'description', 'responsibilities', 'requirements', 'benefits',
                'salary', 'show_salary', 'application_deadline', 'status', 'published_at', 'seo_title', 'seo_description',
            ]);
        });
    }
};
