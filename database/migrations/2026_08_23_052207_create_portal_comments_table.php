<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable');
            $table->foreignId('employee_id')->nullable()->constrained('portal_employees')->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_comments');
    }
};
