<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_oem_followups', function (Blueprint $table) {
            $table->id();
            // Nullable: an OEM authorisation can be chased before any tender needs it.
            $table->foreignId('tender_id')->nullable()->constrained('portal_tenders')->cascadeOnDelete();
            $table->string('oem_name')->index();
            $table->string('requirement_type')->index();
            $table->string('product')->nullable();
            $table->foreignId('requested_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->date('requested_on')->nullable();
            $table->dateTime('required_by')->nullable()->index();
            $table->string('contact_person')->nullable();
            $table->string('contact_channel')->nullable();
            $table->string('status')->default('not_requested')->index();
            $table->dateTime('next_followup_at')->nullable()->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['tender_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_oem_followups');
    }
};
