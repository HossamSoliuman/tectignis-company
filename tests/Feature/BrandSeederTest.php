<?php

use App\Models\Brand;
use Database\Seeders\BrandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function seededPartnerBrands(): array
{
    return [
        ['name' => 'Alada', 'logo' => 'brands/alada-logo-without-background-png-2-zloeocpd.png'],
        ['name' => 'JPCL', 'logo' => 'brands/jpcl-logo-1-j8eulht5.jpg'],
        ['name' => 'Canara Bank', 'logo' => 'brands/canara-bank.webp'],
        ['name' => 'Go Best Dentist', 'logo' => 'brands/go-best-dentist.webp'],
        ['name' => 'LIC', 'logo' => 'brands/lic.webp'],
        ['name' => 'Jewelmyne', 'logo' => 'brands/jewelmyne.webp'],
        ['name' => 'Alignocare', 'logo' => 'brands/alignocare-tectignis.webp'],
    ];
}

it('seeds seven active partner brands in display order', function () {
    $this->seed(BrandSeeder::class);

    expect(
        Brand::query()
            ->active()
            ->ordered()
            ->get(['name', 'logo'])
            ->map(fn (Brand $brand): array => $brand->only(['name', 'logo']))
            ->all(),
    )->toBe(seededPartnerBrands());

    foreach (seededPartnerBrands() as $brand) {
        $this->assertFileExists(public_path('uploads/'.$brand['logo']));
    }
});

it('replaces obsolete seeded partners without affecting admin-created brands', function () {
    Brand::factory()->create(['logo' => 'brands/hp.webp']);
    $adminBrand = Brand::factory()->create(['logo' => 'brands/custom-client.png']);

    $this->seed(BrandSeeder::class);
    $this->seed(BrandSeeder::class);

    expect(Brand::query()->whereIn('logo', collect(seededPartnerBrands())->pluck('logo'))->count())->toBe(7)
        ->and(Brand::query()->where('logo', 'brands/hp.webp')->exists())->toBeFalse();
    $this->assertModelExists($adminBrand);
});
