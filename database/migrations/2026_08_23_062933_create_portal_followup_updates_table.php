<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only follow-up history (§11): every chase is a new row, so the
     * record of who was contacted and when can never be overwritten. Shared by
     * OEM follow-ups now and sales follow-ups in Phase 3, hence the morph.
     */
    public function up(): void
    {
        Schema::create('portal_followup_updates', function (Blueprint $table) {
            $table->id();
            $table->morphs('followupable');
            $table->foreignId('employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->text('note');
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->dateTime('contacted_on')->nullable();
            $table->dateTime('next_followup_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_followup_updates');
    }
};
