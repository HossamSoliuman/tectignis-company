<?php

namespace App\Http\Requests\Admin\Concerns;

trait ValidatesIndustryData
{
    /**
     * Rules shared by the store and update industry requests (everything except
     * the slug, whose uniqueness rule differs between create and update).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function baseIndustryRules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
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
     * Validation rules for the structured, admin-editable `content` JSON of an
     * industry landing page. Every section is optional and degrades gracefully;
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
            'content.hero.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.hero.heading' => ['nullable', 'string', 'max:200'],
            'content.hero.highlight' => ['nullable', 'string', 'max:120'],
            'content.hero.intro' => ['nullable', 'string', 'max:1000'],
            'content.hero.cta_primary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.cta_secondary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.features' => ['nullable', 'array'],
            'content.hero.features.*.icon' => ['nullable', 'string', 'max:255'],
            'content.hero.features.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.hero.features.*.label' => ['nullable', 'string', 'max:120'],
            'content.hero.badges' => ['nullable', 'array'],
            'content.hero.badges.*.icon' => ['nullable', 'string', 'max:255'],
            'content.hero.badges.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.hero.badges.*.label' => ['nullable', 'string', 'max:120'],

            // Section B — Trust logos (global brands)
            'content.trust.enabled' => ['nullable', 'boolean'],

            // Section C — Challenges (left column of the split)
            'content.challenges.enabled' => ['nullable', 'boolean'],
            'content.challenges.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.challenges.heading' => ['nullable', 'string', 'max:200'],
            'content.challenges.subtitle' => ['nullable', 'string', 'max:1000'],
            'content.challenges.items' => ['nullable', 'array'],
            'content.challenges.items.*' => ['nullable', 'string', 'max:300'],

            // Section D — Solutions (right column grid of cards)
            'content.solutions.enabled' => ['nullable', 'boolean'],
            'content.solutions.eyebrow' => ['nullable', 'string', 'max:120'],
            'content.solutions.heading' => ['nullable', 'string', 'max:200'],
            'content.solutions.subtitle' => ['nullable', 'string', 'max:500'],
            'content.solutions.cards' => ['nullable', 'array'],
            'content.solutions.cards.*.icon' => ['nullable', 'string', 'max:255'],
            'content.solutions.cards.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.solutions.cards.*.title' => ['nullable', 'string', 'max:200'],
            'content.solutions.cards.*.description' => ['nullable', 'string', 'max:1000'],

            // Section E — Performance stats
            'content.stats.enabled' => ['nullable', 'boolean'],
            'content.stats.heading' => ['nullable', 'string', 'max:200'],
            'content.stats.subtitle' => ['nullable', 'string', 'max:500'],
            'content.stats.items' => ['nullable', 'array'],
            'content.stats.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.stats.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.stats.items.*.value' => ['nullable', 'string', 'max:40'],
            'content.stats.items.*.label' => ['nullable', 'string', 'max:120'],

            // Section F — Success stories (latest active case studies)
            'content.case_studies.enabled' => ['nullable', 'boolean'],
            'content.case_studies.heading' => ['nullable', 'string', 'max:200'],
            'content.case_studies.subtitle' => ['nullable', 'string', 'max:500'],

            // Section G — Solutions grid (compact icon + title list)
            'content.solutions_grid.enabled' => ['nullable', 'boolean'],
            'content.solutions_grid.heading' => ['nullable', 'string', 'max:200'],
            'content.solutions_grid.subtitle' => ['nullable', 'string', 'max:500'],
            'content.solutions_grid.items' => ['nullable', 'array'],
            'content.solutions_grid.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.solutions_grid.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.solutions_grid.items.*.label' => ['nullable', 'string', 'max:120'],

            // Section H — FAQ + mid-page CTA
            'content.faq.enabled' => ['nullable', 'boolean'],
            'content.faq.heading' => ['nullable', 'string', 'max:200'],
            'content.faq.subtitle' => ['nullable', 'string', 'max:500'],
            'content.faq.items' => ['nullable', 'array'],
            'content.faq.items.*.question' => ['nullable', 'string', 'max:300'],
            'content.faq.items.*.answer' => ['nullable', 'string', 'max:2000'],
            'content.faq.cta_heading' => ['nullable', 'string', 'max:200'],
            'content.faq.cta_text' => ['nullable', 'string', 'max:500'],
            'content.faq.cta_label' => ['nullable', 'string', 'max:60'],

            // Section I — Bottom CTA band
            'content.cta_band.enabled' => ['nullable', 'boolean'],
            'content.cta_band.heading' => ['nullable', 'string', 'max:200'],
            'content.cta_band.subtitle' => ['nullable', 'string', 'max:500'],
            'content.cta_band.button_primary_label' => ['nullable', 'string', 'max:60'],
            'content.cta_band.button_secondary_label' => ['nullable', 'string', 'max:60'],
        ];
    }
}
