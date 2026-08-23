<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_daily_work_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('portal_employees')->cascadeOnDelete();
            $table->date('work_date')->index();
            $table->nullableMorphs('related');
            $table->string('related_category')->index();
            $table->text('activity');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedTinyInteger('progress')->nullable();
            $table->string('status')->default('in_progress')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_daily_work_updates');
    }
};
