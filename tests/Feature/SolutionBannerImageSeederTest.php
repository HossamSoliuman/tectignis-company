<?php

use App\Models\Solution;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\SolutionBannerImageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('links every solution to its generated banner image', function () {
    $this->seed(DatabaseSeeder::class);

    $bannerImages = [
        'erp-solutions' => 'erp-solutions-banner.png',
        'crm-solutions' => 'crm-solutions-banner.png',
        'hrms-solutions' => 'hrms-solutions-banner.png',
        'ai-solutions' => 'ai-solutions-banner.png',
        'cloud-solutions' => 'cloud-solutions-banner.png',
        'cybersecurity-solutions' => 'cybersecurity-solutions-banner.png',
        'automation-solutions' => 'automation-solutions-banner.png',
        'smart-security-solutions' => 'smart-security-solutions-banner.png',
    ];

    foreach ($bannerImages as $slug => $bannerImage) {
        $solution = Solution::query()->where('slug', $slug)->firstOrFail();

        expect($solution->banner_image)->toBe("solutions/{$bannerImage}");
        $this->assertFileExists(public_path("uploads/solutions/{$bannerImage}"));
    }

    $this->get(route('solutions.show', 'erp-solutions'))
        ->assertOk()
        ->assertSee('uploads/solutions/erp-solutions-banner.png', false);

    $this->seed(SolutionBannerImageSeeder::class);

    expect(Solution::query()->whereNotNull('banner_image')->count())->toBe(count($bannerImages));
});

test('displays solution hero banners without cropping their image', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('solutions.show', 'ai-solutions'))
        ->assertSuccessful()
        ->assertSee('ind-hero--solution', false);

    $styles = file_get_contents(public_path('assets/css/custom.css'));

    expect($styles)
        ->toContain('.ind-hero--solution .ind-hero__image')
        ->toContain('height: auto;')
        ->toContain('object-fit: contain;');
});
