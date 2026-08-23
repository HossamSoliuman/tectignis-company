<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tender_clarifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('portal_tenders')->cascadeOnDelete();
            $table->text('question');
            $table->foreignId('raised_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->date('raised_on')->nullable();
            $table->string('submitted_through')->nullable();
            $table->text('response')->nullable();
            $table->date('response_on')->nullable();
            $table->string('status')->default('pending')->index();
            $table->foreignId('attachment_id')->nullable()->constrained('portal_attachments')->nullOnDelete();
            $table->timestamps();

            $table->index(['tender_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tender_clarifications');
    }
};
