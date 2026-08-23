<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tender_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('portal_tenders')->cascadeOnDelete();
            $table->string('requirement');
            $table->string('category')->nullable();
            $table->string('status')->default('pending')->index();
            $table->foreignId('responsible_employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tender_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tender_checklist_items');
    }
};
