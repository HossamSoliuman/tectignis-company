<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

/**
 * The master set of company statistics (spec §10.1). Every template reads
 * these rows by `key`, so a figure is changed in exactly one place.
 */
class StatSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['key' => 'projects', 'label' => 'Projects Delivered', 'value' => '150+', 'sort_order' => 1],
            ['key' => 'clients', 'label' => 'Happy Clients', 'value' => '50+', 'sort_order' => 2],
            ['key' => 'industries', 'label' => 'Industries Served', 'value' => '15+', 'sort_order' => 3],
            ['key' => 'support', 'label' => 'Support', 'value' => '24/7', 'sort_order' => 4],
            ['key' => 'countries', 'label' => 'Countries Served', 'value' => '25+', 'sort_order' => 5],
        ];

        foreach ($stats as $stat) {
            Stat::updateOrCreate(['key' => $stat['key']], array_merge($stat, ['is_active' => true]));
        }
    }
}
