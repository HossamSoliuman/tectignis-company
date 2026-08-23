<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tender_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->unique()->constrained('portal_tenders')->cascadeOnDelete();
            $table->string('technical_result')->nullable();
            $table->string('financial_result')->nullable();
            $table->string('outcome')->default('pending')->index();
            $table->decimal('awarded_value', 15, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tender_results');
    }
};
