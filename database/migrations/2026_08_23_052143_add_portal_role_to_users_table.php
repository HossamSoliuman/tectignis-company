<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Portal access is additive: `role` keeps driving the website CMS, while a
     * null `portal_role` simply means the user has no operations portal access.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('portal_role')->nullable()->index()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('portal_role');
        });
    }
};
