<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only audit trail. There is deliberately no `updated_at`: rows are
     * written once and never edited, and no route exposes update or delete.
     */
    public function up(): void
    {
        Schema::create('portal_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('subject');
            $table->string('event')->index();
            $table->string('description')->nullable();
            $table->json('changes')->nullable();
            $table->string('file_reference')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_activity_logs');
    }
};
