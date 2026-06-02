<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['label' => '+ Projects Delivered', 'value' => '100', 'sort_order' => 1],
            ['label' => '+ Clients Served', 'value' => '50', 'sort_order' => 2],
            ['label' => '+ Years Experience', 'value' => '5', 'sort_order' => 3],
            ['label' => 'Support', 'value' => '24/7', 'sort_order' => 4],
        ];

        foreach ($stats as $stat) {
            Stat::updateOrCreate(['label' => $stat['label']], array_merge($stat, ['is_active' => true]));
        }
    }
}
