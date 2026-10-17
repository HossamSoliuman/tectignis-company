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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('gclid')->nullable()->after('utm_content');
            $table->string('gbraid')->nullable()->after('gclid');
            $table->string('wbraid')->nullable()->after('gbraid');
            $table->string('gad_campaignid', 32)->nullable()->after('wbraid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['gclid', 'gbraid', 'wbraid', 'gad_campaignid']);
        });
    }
};
