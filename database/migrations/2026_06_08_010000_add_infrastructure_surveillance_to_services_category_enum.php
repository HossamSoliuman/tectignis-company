<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the new "infrastructure_surveillance" capability major to the services
     * `category` enum. On SQLite the enum is backed by a CHECK constraint, so we
     * widen the column to a plain string (dropping the old constraint) and
     * re-apply the enum with the expanded set of allowed values.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['software_development', 'ai_automation', 'business_application', 'cloud_security', 'infrastructure_surveillance'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['software_development', 'ai_automation', 'business_application', 'cloud_security'])->change();
        });
    }
};
