<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_task_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('portal_tasks')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('progress')->nullable();
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->dateTime('logged_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_task_updates');
    }
};
