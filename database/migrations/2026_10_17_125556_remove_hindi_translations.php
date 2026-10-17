<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Deletes the Hindi translations and glossary terms now that Hindi is no
 * longer a supported language. The deleted text can't be restored.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('translations')->where('locale', 'hi')->delete();
        DB::table('glossary_terms')->where('locale', 'hi')->delete();
    }

    public function down(): void
    {
        //
    }
};
