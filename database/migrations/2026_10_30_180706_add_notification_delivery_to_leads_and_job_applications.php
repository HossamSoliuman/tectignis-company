<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records whether the internal notification email for each lead and job
 * application went out, so a mail outage shows up in the admin and the
 * notification can be resent instead of only reaching the log.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private const TABLES = ['leads', 'job_applications'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('notified_at')->nullable();
                $table->timestamp('notification_failed_at')->nullable()->index();
                $table->string('notification_error', 500)->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['notification_failed_at']);
                $table->dropColumn(['notified_at', 'notification_failed_at', 'notification_error']);
            });
        }
    }
};
