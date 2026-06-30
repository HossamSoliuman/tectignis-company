<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tectignis.in'],
            [
                'name' => 'Tectignis Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        );

        $this->call([
            SettingsSeeder::class,
            CapabilitySeeder::class,
            SolutionSeeder::class,
            IndustrySeeder::class,
            StatSeeder::class,
            TechStackSeeder::class,
            // ServiceSeeder attaches TechStack/Industry pivots by name, so it must
            // run after those tables are populated.
            ServiceSeeder::class,
            BlogPostSeeder::class,
            CaseStudySeeder::class,
            TestimonialSeeder::class,
            BrandSeeder::class,
            WhyChooseFeatureSeeder::class,
            OfficeLocationSeeder::class,
            GlobalAdvantageSeeder::class,
            ProcessStepSeeder::class,
            ResourceContentSeeder::class,
            RedirectSeeder::class,
        ]);
    }
}
