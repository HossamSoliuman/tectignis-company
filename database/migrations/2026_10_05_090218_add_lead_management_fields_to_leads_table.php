<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns the flat enquiry inbox into a lead pipeline (spec §26.5): qualification
 * fields, landing-page/UTM attribution, a status, an owner, consent and soft
 * deletes so removed leads stay recoverable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('company')->nullable()->after('email');
            $table->string('country')->nullable()->after('company');
            $table->string('service')->nullable()->after('country');
            $table->string('budget')->nullable()->after('message');
            $table->string('timeline')->nullable()->after('budget');
            $table->text('page_url')->nullable()->after('source');
            $table->string('utm_source')->nullable()->after('page_url');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');
            $table->string('status')->default('new')->after('utm_content')->index();
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('consented_at')->nullable()->after('is_read');
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'company', 'country', 'service', 'budget', 'timeline', 'page_url',
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                'status', 'assigned_to', 'consented_at',
            ]);
        });
    }
};
