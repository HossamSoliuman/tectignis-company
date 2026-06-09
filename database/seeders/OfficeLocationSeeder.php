<?php

namespace Database\Seeders;

use App\Models\OfficeLocation;
use Illuminate\Database\Seeder;

class OfficeLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            // India
            ['region' => 'india', 'city' => 'Navi Mumbai', 'type' => 'Head Office'],
            ['region' => 'india', 'city' => 'Mumbai', 'type' => 'Corporate Office'],
            ['region' => 'india', 'city' => 'Thane', 'type' => 'Regional Office'],
            ['region' => 'india', 'city' => 'Pune', 'type' => 'Development Center'],
            ['region' => 'india', 'city' => 'Panvel', 'type' => 'Operations Center'],
            ['region' => 'india', 'city' => 'Maharashtra', 'type' => 'Wide Service Network'],
            ['region' => 'india', 'city' => 'India', 'type' => 'Nationwide Delivery'],

            // Global
            ['region' => 'global', 'city' => 'UAE', 'type' => 'Middle East Operations'],
            ['region' => 'global', 'city' => 'Saudi Arabia', 'type' => 'Regional Delivery Partner'],
            ['region' => 'global', 'city' => 'UK', 'type' => 'European Operations'],
            ['region' => 'global', 'city' => 'USA', 'type' => 'North America Operations'],
            ['region' => 'global', 'city' => 'Canada', 'type' => 'North America Partner'],
            ['region' => 'global', 'city' => 'Australia', 'type' => 'Asia Pacific Operations'],
            ['region' => 'global', 'city' => 'Singapore', 'type' => 'Southeast Asia Partner'],
        ];

        foreach ($locations as $index => $location) {
            OfficeLocation::updateOrCreate(
                ['region' => $location['region'], 'city' => $location['city']],
                array_merge($location, ['sort_order' => $index + 1, 'is_active' => true]),
            );
        }
    }
}
