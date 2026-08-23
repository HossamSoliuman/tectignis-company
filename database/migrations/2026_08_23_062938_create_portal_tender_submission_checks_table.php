<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tender_submission_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('portal_tenders')->cascadeOnDelete();
            $table->string('label');
            $table->boolean('is_checked')->default(false);
            $table->foreignId('checked_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->dateTime('checked_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tender_id', 'is_checked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tender_submission_checks');
    }
};
