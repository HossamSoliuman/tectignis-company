<?php

use App\Models\Industry;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\IndustryBannerImageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('links every industry to its copied banner image', function () {
    $this->seed(DatabaseSeeder::class);

    $bannerImages = [
        'manufacturing' => 'manufacturing-banner.png',
        'healthcare' => 'healthcare-banner.png',
        'education' => 'education-banner.png',
        'retail' => 'retail-banner.png',
        'real-estate' => 'real-estate-banner.png',
        'logistics' => 'logistics-banner.png',
        'hospitality' => 'hospitality-banner.png',
        'corporate-offices' => 'corporate-offices-banner.png',
        'finance-banking' => 'finance-banking-banner-v2.png',
        'ecommerce' => 'ecommerce-banner-v2.png',
        'government' => 'government-banner-v2.png',
        'startups' => 'startups-banner-v2.png',
        'travel-tourism' => 'travel-tourism-banner-v2.png',
    ];

    foreach ($bannerImages as $slug => $bannerImage) {
        $industry = Industry::query()->where('slug', $slug)->firstOrFail();

        expect($industry->banner_image)->toBe("industries/{$bannerImage}");
        $this->assertFileExists(public_path("uploads/industries/{$bannerImage}"));
    }

    $this->get(route('industries.show', 'manufacturing'))
        ->assertOk()
        ->assertSee('uploads/industries/manufacturing-banner.png', false);

    $this->seed(IndustryBannerImageSeeder::class);

    expect(Industry::query()->whereNotNull('banner_image')->count())->toBe(count($bannerImages));
});

test('links legacy industry slugs to their banner images', function () {
    $legacyBannerImages = [
        'banking-finance-services' => 'finance-banking-banner-v2.png',
        'government-public-sector' => 'government-banner-v2.png',
        'startups-smes-growing-businesses' => 'startups-banner-v2.png',
        'warehousing-logistics-smart-facilities' => 'travel-tourism-banner-v2.png',
    ];

    foreach (array_keys($legacyBannerImages) as $slug) {
        Industry::factory()->create(['slug' => $slug]);
    }

    $this->seed(IndustryBannerImageSeeder::class);

    foreach ($legacyBannerImages as $slug => $bannerImage) {
        expect(Industry::query()->where('slug', $slug)->value('banner_image'))
            ->toBe("industries/{$bannerImage}");
    }
});
