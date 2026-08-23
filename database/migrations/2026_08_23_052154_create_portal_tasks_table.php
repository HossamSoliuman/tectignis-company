<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->string('priority')->default('medium')->index();
            $table->string('status')->default('not_started')->index();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->dateTime('start_date')->nullable();
            // Date *and* time: deadlines in this business are hour-sensitive.
            $table->dateTime('due_date')->nullable()->index();
            $table->dateTime('completed_at')->nullable();
            // Polymorphic owner (Tender, SalesLead, Customer, …) — null for standalone tasks.
            $table->nullableMorphs('related');
            $table->boolean('is_overdue')->default(false)->index();
            $table->dateTime('overdue_at')->nullable();
            $table->unsignedInteger('reopened_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['assigned_to_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_tasks');
    }
};
