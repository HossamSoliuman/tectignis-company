<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Alada', 'logo' => 'brands/alada-logo-without-background-png-2-zloeocpd.png'],
            ['name' => 'JPCL', 'logo' => 'brands/jpcl-logo-1-j8eulht5.jpg'],
            ['name' => 'Canara Bank', 'logo' => 'brands/canara-bank.webp'],
            ['name' => 'Go Best Dentist', 'logo' => 'brands/go-best-dentist.webp'],
            ['name' => 'LIC', 'logo' => 'brands/lic.webp'],
            ['name' => 'Jewelmyne', 'logo' => 'brands/jewelmyne.webp'],
            ['name' => 'Alignocare', 'logo' => 'brands/alignocare-tectignis.webp'],
        ];

        $obsoleteSeededLogos = [
            'brands/tajushashariapect-tectignis.webp',
            'brands/perfect-packing-solution.webp',
            'brands/syncat.webp',
            'brands/harmony-school.webp',
            'brands/gov-of-maharashtra.webp',
            'brands/reliance-petroleum.webp',
            'brands/hp.webp',
            'brands/shree-aai-pratishtahan.webp',
            'brands/raul-engineering.webp',
        ];

        Brand::query()->whereIn('logo', $obsoleteSeededLogos)->delete();

        foreach ($brands as $index => $brand) {
            Brand::updateOrCreate(
                ['logo' => $brand['logo']],
                [
                    'name' => $brand['name'],
                    'logo' => $brand['logo'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
