<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_employees', function (Blueprint $table) {
            $table->id();
            // Nullable: staff can be recorded before (or without) a login account.
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('portal_departments')->nullOnDelete();
            $table->string('designation')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('status')->default('active')->index();
            $table->foreignId('reports_to_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_employees');
    }
};
