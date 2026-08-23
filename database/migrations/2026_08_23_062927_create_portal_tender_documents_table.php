<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tender_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('portal_tenders')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->index();
            $table->boolean('is_required')->default(true);
            $table->string('status')->default('pending')->index();
            $table->foreignId('responsible_employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->date('requested_on')->nullable();
            $table->dateTime('required_by')->nullable()->index();
            $table->foreignId('attachment_id')->nullable()->constrained('portal_attachments')->nullOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->date('expires_on')->nullable();
            $table->text('remarks')->nullable();
            // Management sign-off (§10): a document is only trusted once checked.
            $table->foreignId('verified_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tender_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tender_documents');
    }
};
