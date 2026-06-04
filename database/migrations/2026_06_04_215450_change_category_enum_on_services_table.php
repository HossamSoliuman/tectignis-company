<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Reconciles the legacy two-category enum onto the three design categories.
     * Done in three steps so it works on SQLite's CHECK-constraint-backed enums:
     * widen to a plain string (drops the old constraint), remap the data, then
     * re-apply the enum with the new allowed values.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->change();
        });

        DB::table('services')->where('category', 'digital_services')->update(['category' => 'software_development']);
        DB::table('services')->where('category', 'it_services')->update(['category' => 'business_application']);

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['software_development', 'ai_automation', 'business_application'])->change();
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

        DB::table('services')->where('category', 'software_development')->update(['category' => 'digital_services']);
        DB::table('services')->whereIn('category', ['ai_automation', 'business_application'])->update(['category' => 'it_services']);

        Schema::table('services', function (Blueprint $table) {
            $table->enum('category', ['it_services', 'digital_services'])->change();
        });
    }
};
