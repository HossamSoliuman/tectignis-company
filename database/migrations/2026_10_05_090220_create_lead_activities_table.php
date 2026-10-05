<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Append-only audit trail of important lead changes (spec §26.6 "Audit"):
 * creation, status and assignment changes, notes, deletes and restores.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};
