<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Candidate applications, kept apart from business leads at the database
 * level (spec §26.8, §26.10, §27). The job title and designation name are
 * snapshotted so history survives renames, and neither relation cascades
 * deletes (spec §28.11).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('job_title')->nullable();
            $table->foreignId('designation_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('designation_name')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('location')->nullable();
            $table->string('experience')->nullable();
            $table->string('current_role')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('resume_name')->nullable();
            $table->string('resume_mime')->nullable();
            $table->unsignedInteger('resume_size')->nullable();
            $table->string('status')->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_read')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
