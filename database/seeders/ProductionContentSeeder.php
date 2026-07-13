<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WebDevelopmentContentSeeder::class,
            AiAutomationContentSeeder::class,
            CloudSecurityContentSeeder::class,
            InfrastructureSurveillanceContentSeeder::class,
        ]);
    }
}
