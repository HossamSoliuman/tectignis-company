<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['slug' => 'manufacturing', 'name' => 'Manufacturing', 'icon' => 'fas fa-industry', 'short_description' => 'Smart manufacturing, ERP, and automation solutions for production-driven businesses.', 'sort_order' => 1],
            ['slug' => 'healthcare', 'name' => 'Healthcare', 'icon' => 'fas fa-heartbeat', 'short_description' => 'HIPAA-ready software, hospital management, and telemedicine platforms.', 'sort_order' => 2],
            ['slug' => 'education', 'name' => 'Education', 'icon' => 'fas fa-graduation-cap', 'short_description' => 'LMS, school ERP, and e-learning solutions for modern institutions.', 'sort_order' => 3],
            ['slug' => 'retail', 'name' => 'Retail', 'icon' => 'fas fa-shopping-cart', 'short_description' => 'E-commerce, POS, and inventory systems for retail and distribution.', 'sort_order' => 4],
            ['slug' => 'real-estate', 'name' => 'Real Estate', 'icon' => 'fas fa-building', 'short_description' => 'Property management, CRM, and digital marketing for real estate firms.', 'sort_order' => 5],
            ['slug' => 'logistics', 'name' => 'Logistics', 'icon' => 'fas fa-truck', 'short_description' => 'Fleet tracking, warehouse, and supply-chain management solutions.', 'sort_order' => 6],
            ['slug' => 'hospitality', 'name' => 'Hospitality', 'icon' => 'fas fa-concierge-bell', 'short_description' => 'Booking engines, PMS, and guest experience platforms for hospitality.', 'sort_order' => 7],
            ['slug' => 'corporate-offices', 'name' => 'Corporate Offices', 'icon' => 'fas fa-briefcase', 'short_description' => 'IT infrastructure, security, and productivity solutions for corporates.', 'sort_order' => 8],
        ];

        foreach ($industries as $industry) {
            Industry::updateOrCreate(['slug' => $industry['slug']], array_merge($industry, ['is_active' => true]));
        }
    }
}
