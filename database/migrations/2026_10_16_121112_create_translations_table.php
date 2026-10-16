<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type', 50);
            $table->unsignedBigInteger('translatable_id');
            $table->string('field');
            $table->string('locale', 8);
            $table->longText('value');
            $table->char('source_hash', 40);
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'field', 'locale'], 'translations_unique');
            $table->index(['translatable_type', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
