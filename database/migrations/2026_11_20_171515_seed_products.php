<?php

use App\Models\Product;
use App\Models\Setting;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds TenderSet, Tectignis Accounting and Tectignis QR to the homepage's
 * Our Products section on existing installs, with their reviewed
 * translations. The seeder skips any product that already exists, so
 * products edited in the admin keep their changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new ProductSeeder)->run();
        app()->call([new TranslationSeeder, 'run'], ['idPrefix' => 'product.']);
        Setting::flushSiteCache();
    }

    public function down(): void
    {
        $names = array_column((new ProductSeeder)->products(), 'name');

        Product::whereIn('name', $names)->get()->each->delete();
        Setting::flushSiteCache();
    }
};
