<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class IndustryBannerImageSeeder extends Seeder
{
    /**
     * Link industry records to their banner images.
     *
     * Images live in public/uploads/industries/ and the column stores the
     * path relative to public/uploads/ (rendered via asset('uploads/'.$path)).
     * Safe to re-run: only existing industries with a present banner are touched.
     */
    public function run(): void
    {
        foreach ($this->bannerImages() as $slug => $fileName) {
            if (! File::exists(public_path("uploads/industries/{$fileName}"))) {
                $this->command?->warn("Skipped: banner file missing for [{$slug}] ({$fileName}).");

                continue;
            }

            $industry = Industry::query()->where('slug', $slug)->first();

            if (! $industry) {
                $this->command?->warn("Skipped: no industry found for slug [{$slug}].");

                continue;
            }

            $industry->update(['banner_image' => "industries/{$fileName}"]);
        }
    }

    /**
     * Map of industry slug to its banner image file name inside uploads/industries.
     *
     * @return array<string, string>
     */
    private function bannerImages(): array
    {
        return [
            'manufacturing' => 'manufacturing-banner.png',
            'healthcare' => 'healthcare-banner.png',
            'education' => 'education-banner.png',
            'retail' => 'retail-banner.png',
            'real-estate' => 'real-estate-banner.png',
            'logistics' => 'logistics-banner.png',
            'hospitality' => 'hospitality-banner.png',
            'corporate-offices' => 'corporate-offices-banner.png',
            'finance-banking' => 'finance-banking-banner-v2.png',
            'banking-finance-services' => 'finance-banking-banner-v2.png',
            'ecommerce' => 'ecommerce-banner-v2.png',
            'government' => 'government-banner-v2.png',
            'government-public-sector' => 'government-banner-v2.png',
            'startups' => 'startups-banner-v2.png',
            'startups-smes-growing-businesses' => 'startups-banner-v2.png',
            'travel-tourism' => 'travel-tourism-banner-v2.png',
            'warehousing-logistics-smart-facilities' => 'travel-tourism-banner-v2.png',
        ];
    }
}
