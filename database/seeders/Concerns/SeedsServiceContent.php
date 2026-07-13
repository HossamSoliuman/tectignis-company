<?php

namespace Database\Seeders\Concerns;

use App\Models\Capability;
use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;

trait SeedsServiceContent
{
    /**
     * @param  array{slug: string, category: string, title: string, short_description: string, icon?: ?string, banner_image?: ?string, sort_order: int}  $spec
     */
    protected function seedCapability(array $spec): Capability
    {
        $existingCapability = Capability::query()
            ->where('slug', $spec['slug'])
            ->first();

        return Capability::query()->updateOrCreate(
            ['slug' => $spec['slug']],
            [
                'category' => $spec['category'],
                'title' => $existingCapability?->title ?: $spec['title'],
                'short_description' => $spec['short_description'],
                'icon' => $spec['icon'] ?? null,
                'banner_image' => $spec['banner_image'] ?? null,
                'sort_order' => $spec['sort_order'],
                'is_active' => true,
                'show_in_menu' => true,
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $spec
     */
    protected function seedService(Capability $capability, array $spec): Service
    {
        $techNames = $spec['tech_stacks'] ?? [];
        $industryNames = $spec['industries'] ?? [];

        $service = Service::query()->updateOrCreate(
            ['slug' => $spec['slug']],
            [
                'title' => $spec['title'],
                'capability_id' => $capability->id,
                'category' => $spec['category'],
                'icon' => $spec['icon'] ?? null,
                'banner_image' => $spec['banner_image'] ?? null,
                'sort_order' => $spec['sort_order'],
                'short_description' => $spec['short_description'],
                'seo_title' => $spec['seo_title'],
                'seo_description' => $spec['seo_description'],
                'content' => $this->buildContent($spec),
                'is_active' => true,
            ],
        );

        $service->techStacks()->sync(TechStack::query()->whereIn('name', $techNames)->pluck('id'));
        $service->industries()->sync(Industry::query()->whereIn('name', $industryNames)->pluck('id'));

        return $service;
    }

    /**
     * @param  array<string, mixed>  $spec
     * @return array<string, mixed>
     */
    protected function buildContent(array $spec): array
    {
        $title = $spec['title'];

        return [
            'hero' => [
                'eyebrow' => $spec['eyebrow'] ?? $title,
                'heading' => $spec['heading'],
                'intro' => $spec['intro'],
                'bullets' => $spec['bullets'],
                'cta_primary_label' => $spec['cta_primary'] ?? 'Get Free Consultation',
                'cta_secondary_label' => $spec['cta_secondary'] ?? 'View Our Work',
            ],
            'features_strip' => [
                'enabled' => true,
                'items' => $this->withNullIcons($spec['features'] ?? $this->defaultFeatures()),
            ],
            'sub_services' => [
                'enabled' => true,
                'subtitle' => $spec['sub_subtitle'] ?? 'What We Offer',
                'heading' => $spec['sub_heading'] ?? "Our {$title} Services",
                'items' => $this->withNullIcons($spec['sub_services']),
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => $spec['process_heading'] ?? 'Our Simple Step-by-Step Process',
                'steps' => $this->withNullIcons($spec['process'] ?? $this->defaultProcess()),
            ],
            'tech' => [
                'enabled' => true,
                'subtitle' => 'Our Stack',
                'heading' => 'Technologies We Use',
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Who We Serve',
                'heading' => 'Industries We Serve',
            ],
            'case_studies' => [
                'enabled' => true,
                'subtitle' => 'Proof of Work',
                'heading' => 'Our Recent Success Stories',
            ],
            'why_choose' => [
                'enabled' => true,
                'subtitle' => 'The Tectignis Difference',
                'heading' => $spec['why_heading'] ?? "Why Choose Tectignis for {$title}",
                'points' => $spec['why_points'],
                'cta_label' => 'Get a Free Quote',
            ],
            'faq' => [
                'enabled' => true,
                'heading' => 'Frequently Asked Questions',
                'items' => $spec['faqs'],
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => $spec['cta_heading'] ?? "Ready to Kickstart Your {$title} Journey?",
                'button_label' => $spec['cta_button'] ?? 'Get a Free Consultation',
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function withNullIcons(array $rows): array
    {
        return array_map(fn (array $row) => array_merge(['icon' => null], $row), $rows);
    }

    /**
     * @return array<int, array{label: string}>
     */
    protected function defaultFeatures(): array
    {
        return [
            ['label' => 'On-Time Delivery'],
            ['label' => 'Agile Process'],
            ['label' => 'Dedicated Team'],
            ['label' => 'Post-Launch Support'],
        ];
    }

    /**
     * @return array<int, array{title: string, description: string}>
     */
    protected function defaultProcess(): array
    {
        return [
            ['title' => 'Requirement Analysis', 'description' => 'We map your goals, users and requirements into a clear, costed project blueprint.'],
            ['title' => 'UI/UX Design', 'description' => 'Intuitive, on-brand interfaces validated with you before development begins.'],
            ['title' => 'Development', 'description' => 'Working software built in short, transparent sprints using modern technology.'],
            ['title' => 'Testing & QA', 'description' => 'Rigorous manual and automated testing for performance, security and reliability.'],
            ['title' => 'Deployment', 'description' => 'Smooth, low-risk launch with data migration and production hardening.'],
            ['title' => 'Support & Maintenance', 'description' => 'Ongoing updates, monitoring and enhancements that keep you ahead.'],
        ];
    }
}
