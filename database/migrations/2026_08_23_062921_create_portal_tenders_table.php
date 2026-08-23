<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tenders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('tender_number')->index();
            $table->string('title');
            $table->string('customer_organization')->nullable();
            $table->string('portal')->default('other')->index();
            $table->string('tender_url')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('pre_bid_at')->nullable();
            $table->dateTime('submission_start_at')->nullable();
            // The deadline the whole workspace counts down to.
            $table->dateTime('submission_deadline_at')->nullable()->index();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->boolean('emd_required')->default(false);
            $table->decimal('emd_amount', 15, 2)->nullable();
            $table->boolean('fee_required')->default(false);
            $table->decimal('fee_amount', 15, 2)->nullable();
            $table->foreignId('assigned_employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->foreignId('technical_owner_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->foreignId('sales_owner_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->string('stage')->default('identified')->index();
            $table->string('decision')->default('under_review')->index();
            $table->string('eligibility_status')->default('under_review');
            $table->text('eligibility_override_reason')->nullable();
            $table->text('notes')->nullable();
            // Cached §28 readiness so index screens do not recompute per row.
            $table->unsignedTinyInteger('completion_percent')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['stage', 'submission_deadline_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tenders');
    }
};
