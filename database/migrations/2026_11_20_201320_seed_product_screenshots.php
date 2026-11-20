<?php

use App\Models\Product;
use App\Models\Setting;
use Database\Seeders\ProductSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Starts the screenshots of TenderSet and Tectignis QR from their case
 * studies' cover and screenshots. Screenshots already added in the admin
 * are kept.
 */
return new class extends Migration
{
    public function up(): void
    {
        $seeder = new ProductSeeder;

        foreach ($seeder->products() as $seeded) {
            $product = Product::with('caseStudy')->where('name', $seeded['name'])->first();

            if ($product?->caseStudy && $product->galleryImages() === []) {
                $product->update(['gallery' => $seeder->screenshotsFrom($product->caseStudy)]);
            }
        }

        Setting::flushSiteCache();
    }

    public function down(): void
    {
        $names = array_column((new ProductSeeder)->products(), 'name');

        Product::whereIn('name', $names)->update(['gallery' => null]);
        Setting::flushSiteCache();
    }
};
