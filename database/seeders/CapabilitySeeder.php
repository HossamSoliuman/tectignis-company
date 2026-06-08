<?php

namespace Database\Seeders;

use App\Models\Capability;
use Illuminate\Database\Seeder;

class CapabilitySeeder extends Seeder
{
    public function run(): void
    {
        // Capabilities are the header columns of the public mega-menu. Each is a
        // "major" expertise area; the services attached to it (Service.capability_id)
        // render as the column's sub-list. Order here = left-to-right column order.
        $capabilities = [
            ['software_development', 'software-development', 'Software Development', 'Custom web, mobile, SaaS and enterprise software engineered around your business.', 'Customize-Software-Development1.webp'],
            ['business_application', 'business-applications', 'Business Applications', 'Ready-to-deploy management software for every department and industry.', 'Custom-Software.webp'],
            ['ai_automation', 'ai-automation', 'AI & Automation', 'AI, machine learning and automation that cut costs and unlock growth.', 'Artificial-Intelligence.webp'],
            ['cloud_security', 'cloud-security', 'Cloud & Security', 'Cloud consulting, migration and cybersecurity that keep you fast, scalable and protected.', 'cloud-security.png'],
        ];

        foreach ($capabilities as $index => [$category, $slug, $title, $shortDescription, $icon]) {
            Capability::firstOrCreate(
                ['slug' => $slug],
                [
                    'category' => $category,
                    'title' => $title,
                    'short_description' => $shortDescription,
                    'icon' => 'capabilities/'.$icon,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        // Remove any capability that isn't one of the current majors (e.g. the
        // legacy per-service capabilities). Services point here with nullOnDelete,
        // so ServiceSeeder re-attaches them to the new majors by category.
        Capability::whereNotIn('slug', array_column($capabilities, 1))->delete();
    }
}
