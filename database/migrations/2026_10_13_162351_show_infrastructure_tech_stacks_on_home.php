<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * The home page lays technology groups out four to a row, and live installs
 * showed seven, leaving an empty slot. Showing a few infrastructure brands
 * brings the Infrastructure group in as the eighth card.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $names = ['Cisco', 'Fortinet', 'Dell', 'VMware'];

    public function up(): void
    {
        DB::table('tech_stacks')
            ->where('category', 'infrastructure')
            ->whereIn('name', $this->names)
            ->update(['show_on_home' => true, 'is_active' => true, 'updated_at' => now()]);

        Cache::forget('site.home');
    }

    public function down(): void
    {
        DB::table('tech_stacks')
            ->where('category', 'infrastructure')
            ->whereIn('name', $this->names)
            ->update(['show_on_home' => false, 'updated_at' => now()]);

        Cache::forget('site.home');
    }
};
