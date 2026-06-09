<?php

namespace Database\Seeders;

use App\Models\WhyChooseFeature;
use Illuminate\Database\Seeder;

class WhyChooseFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['icon' => 'fas fa-globe', 'title' => 'Global Delivery Model', 'text' => 'We leverage a global talent pool to deliver round-the-clock progress on your projects.'],
            ['icon' => 'fas fa-coins', 'title' => 'Cost-Effective Solutions', 'text' => 'Enterprise-grade quality at competitive pricing, without compromising on standards.'],
            ['icon' => 'fas fa-tasks', 'title' => 'Dedicated Project Management', 'text' => 'A dedicated manager keeps every milestone on track from kickoff to delivery.'],
            ['icon' => 'fas fa-expand-arrows-alt', 'title' => 'Scalable Resources', 'text' => 'Scale your team up or down on demand to match evolving project needs.'],
            ['icon' => 'fas fa-vial', 'title' => 'Quality Assurance & Testing', 'text' => 'Rigorous QA and testing at every stage to ensure reliable, bug-free releases.'],
            ['icon' => 'fas fa-clock', 'title' => 'On-Time Delivery', 'text' => 'Disciplined planning and execution that consistently meets agreed deadlines.'],
            ['icon' => 'fas fa-headset', 'title' => 'Single Point of Contact', 'text' => 'One reliable point of contact for clear, streamlined communication.'],
            ['icon' => 'fas fa-layer-group', 'title' => 'Multi-Domain Expertise', 'text' => 'Cross-industry experience that brings proven best practices to your domain.'],
        ];

        foreach ($features as $index => $feature) {
            WhyChooseFeature::updateOrCreate(
                ['title' => $feature['title']],
                array_merge($feature, ['sort_order' => $index + 1, 'is_active' => true]),
            );
        }
    }
}
