<?php

namespace App\Http\Requests\Admin\Concerns;

trait ValidatesSolutionData
{
    /**
     * Rules shared by the store and update solution requests (everything except
     * the slug, whose uniqueness rule differs between create and update).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function baseSolutionRules(): array
    {
        return array_merge([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'file', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ], $this->contentRules());
    }

    /**
     * Validation rules for the structured, admin-editable `content` JSON of a
     * solution landing page. Every section is optional and degrades gracefully;
     * toggles are booleans and per-item icons are optional image uploads
     * (`*_file`) alongside the hidden filename (kept when no new file is chosen).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function contentRules(): array
    {
        return [
            'content' => ['nullable', 'array'],

            // Section A — Hero
            'content.hero' => ['nullable', 'array'],
            'content.hero.theme' => ['nullable', 'string', 'in:light,dark'],
            'content.hero.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.hero.heading' => ['nullable', 'string', 'max:200'],
            'content.hero.highlight' => ['nullable', 'string', 'max:120'],
            'content.hero.intro' => ['nullable', 'string', 'max:1000'],
            'content.hero.cta_primary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.cta_secondary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.benefits' => ['nullable', 'array'],
            'content.hero.benefits.*' => ['nullable', 'string', 'max:200'],
            'content.hero.badges' => ['nullable', 'array'],
            'content.hero.badges.*.icon' => ['nullable', 'string', 'max:255'],
            'content.hero.badges.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.hero.badges.*.label' => ['nullable', 'string', 'max:120'],

            // Section B — Statistics (KPI cards)
            'content.stats.enabled' => ['nullable', 'boolean'],
            'content.stats.heading' => ['nullable', 'string', 'max:200'],
            'content.stats.subtitle' => ['nullable', 'string', 'max:500'],
            'content.stats.items' => ['nullable', 'array'],
            'content.stats.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.stats.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.stats.items.*.value' => ['nullable', 'string', 'max:40'],
            'content.stats.items.*.label' => ['nullable', 'string', 'max:120'],

            // Section C — Modules grid (icon + title + description + Learn More)
            'content.modules.enabled' => ['nullable', 'boolean'],
            'content.modules.heading' => ['nullable', 'string', 'max:200'],
            'content.modules.subtitle' => ['nullable', 'string', 'max:500'],
            'content.modules.cards' => ['nullable', 'array'],
            'content.modules.cards.*.icon' => ['nullable', 'string', 'max:255'],
            'content.modules.cards.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.modules.cards.*.title' => ['nullable', 'string', 'max:200'],
            'content.modules.cards.*.description' => ['nullable', 'string', 'max:1000'],

            // Section D — Benefits grid (icon + title + description)
            'content.benefits.enabled' => ['nullable', 'boolean'],
            'content.benefits.heading' => ['nullable', 'string', 'max:200'],
            'content.benefits.subtitle' => ['nullable', 'string', 'max:500'],
            'content.benefits.items' => ['nullable', 'array'],
            'content.benefits.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.benefits.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.benefits.items.*.title' => ['nullable', 'string', 'max:200'],
            'content.benefits.items.*.description' => ['nullable', 'string', 'max:1000'],

            // Section E — Implementation process stepper
            'content.process.enabled' => ['nullable', 'boolean'],
            'content.process.heading' => ['nullable', 'string', 'max:200'],
            'content.process.subtitle' => ['nullable', 'string', 'max:500'],
            'content.process.steps' => ['nullable', 'array'],
            'content.process.steps.*.icon' => ['nullable', 'string', 'max:255'],
            'content.process.steps.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.process.steps.*.title' => ['nullable', 'string', 'max:200'],
            'content.process.steps.*.description' => ['nullable', 'string', 'max:1000'],

            // Section F — Industry solutions (compact icon + title list from catalog)
            'content.industries.enabled' => ['nullable', 'boolean'],
            'content.industries.heading' => ['nullable', 'string', 'max:200'],
            'content.industries.subtitle' => ['nullable', 'string', 'max:500'],
            'content.industries.cta_label' => ['nullable', 'string', 'max:60'],

            // Section G — Why choose (features + supporting image + testimonial)
            'content.why_choose.enabled' => ['nullable', 'boolean'],
            'content.why_choose.heading' => ['nullable', 'string', 'max:200'],
            'content.why_choose.subtitle' => ['nullable', 'string', 'max:500'],
            'content.why_choose.image' => ['nullable', 'string', 'max:255'],
            'content.why_choose.image_file' => ['nullable', 'image', 'max:4096'],
            'content.why_choose.points' => ['nullable', 'array'],
            'content.why_choose.points.*' => ['nullable', 'string', 'max:200'],
            'content.why_choose.testimonial_quote' => ['nullable', 'string', 'max:1000'],
            'content.why_choose.testimonial_author' => ['nullable', 'string', 'max:120'],
            'content.why_choose.testimonial_role' => ['nullable', 'string', 'max:160'],
            'content.why_choose.testimonial_cta_label' => ['nullable', 'string', 'max:60'],

            // Section H — Final CTA band
            'content.cta_band.enabled' => ['nullable', 'boolean'],
            'content.cta_band.heading' => ['nullable', 'string', 'max:200'],
            'content.cta_band.subtitle' => ['nullable', 'string', 'max:500'],
            'content.cta_band.button_primary_label' => ['nullable', 'string', 'max:60'],
            'content.cta_band.button_secondary_label' => ['nullable', 'string', 'max:60'],
        ];
    }
}
