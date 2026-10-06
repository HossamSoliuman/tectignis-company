<?php

use App\Enums\Portal\PortalRole;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Portal roles can only be granted from inside the portal (Operations →
 * Employees), and only `PortalSeeder` hands out the first one. Installs that
 * were migrated but never seeded therefore had nobody able to open the portal,
 * so Super Admins without a portal role become Directors.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', UserRole::SuperAdmin->value)
            ->whereNull('portal_role')
            ->update(['portal_role' => PortalRole::Director->value]);
    }

    /**
     * Not reversed: the granted roles can't be told apart from ones assigned
     * through the UI afterwards.
     */
    public function down(): void
    {
        //
    }
};
