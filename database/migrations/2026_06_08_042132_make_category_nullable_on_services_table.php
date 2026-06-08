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
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['software_development', 'ai_automation', 'business_application', 'cloud_security', 'infrastructure_surveillance'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['software_development', 'ai_automation', 'business_application', 'cloud_security', 'infrastructure_surveillance'])->nullable(false)->change();
        });
    }
};
