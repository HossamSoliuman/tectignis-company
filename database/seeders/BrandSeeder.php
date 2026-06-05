<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['slug' => 'canara-bank', 'name' => 'Canara Bank'],
            ['slug' => 'go-best-dentist', 'name' => 'Go Best Dentist'],
            ['slug' => 'tajushashariapect-tectignis', 'name' => 'Tajushashariapect'],
            ['slug' => 'lic', 'name' => 'LIC'],
            ['slug' => 'jewelmyne', 'name' => 'Jewelmyne'],
            ['slug' => 'alignocare-tectignis', 'name' => 'Alignocare'],
            ['slug' => 'perfect-packing-solution', 'name' => 'Perfect Packing Solution'],
            ['slug' => 'syncat', 'name' => 'Syncat'],
            ['slug' => 'harmony-school', 'name' => 'Harmony School'],
            ['slug' => 'gov-of-maharashtra', 'name' => 'Government of Maharashtra'],
            ['slug' => 'reliance-petroleum', 'name' => 'Reliance Petroleum'],
            ['slug' => 'hp', 'name' => 'HP'],
            ['slug' => 'shree-aai-pratishtahan', 'name' => 'Shree Aai Pratishtahan'],
            ['slug' => 'raul-engineering', 'name' => 'Raul Engineering'],
        ];

        foreach ($brands as $index => $brand) {
            Brand::updateOrCreate(
                ['logo' => 'brands/'.$brand['slug'].'.webp'],
                [
                    'name' => $brand['name'],
                    'logo' => 'brands/'.$brand['slug'].'.webp',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
