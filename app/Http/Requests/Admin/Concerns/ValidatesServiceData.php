<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\Service;
use Illuminate\Validation\Rule;

trait ValidatesServiceData
{
    /**
     * Rules shared by the store and update requests (everything except the slug,
     * whose uniqueness rule differs between create and update).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function baseServiceRules(): array
    {
        return array_merge([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', Rule::in(Service::CATEGORIES)],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'file', 'image', 'max:2048'],
            'banner_image' => ['nullable', 'file', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],

            // Shared-catalog attachments (Section E/F pivots).
            'tech_stacks' => ['nullable', 'array'],
            'tech_stacks.*' => ['integer', 'exists:tech_stacks,id'],
            'industries' => ['nullable', 'array'],
            'industries.*' => ['integer', 'exists:industries,id'],
        ], $this->contentRules());
    }

    /**
     * Validation rules for the structured, admin-editable `content` JSON.
     * Every section is optional and degrades gracefully; toggles are booleans
     * and per-item icons are optional image uploads (`*_file`) alongside the
     * hidden filename (kept when no new file is chosen).
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
            'content.hero.intro' => ['nullable', 'string', 'max:1000'],
            'content.hero.cta_primary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.cta_secondary_label' => ['nullable', 'string', 'max:60'],
            'content.hero.bullets' => ['nullable', 'array'],
            'content.hero.bullets.*' => ['nullable', 'string', 'max:200'],

            // Section B — Feature/Trust strip
            'content.features_strip.enabled' => ['nullable', 'boolean'],
            'content.features_strip.items' => ['nullable', 'array'],
            'content.features_strip.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.features_strip.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.features_strip.items.*.label' => ['nullable', 'string', 'max:120'],

            // Section C — Sub-services grid
            'content.sub_services.enabled' => ['nullable', 'boolean'],
            'content.sub_services.heading' => ['nullable', 'string', 'max:200'],
            'content.sub_services.subtitle' => ['nullable', 'string', 'max:500'],
            'content.sub_services.items' => ['nullable', 'array'],
            'content.sub_services.items.*.icon' => ['nullable', 'string', 'max:255'],
            'content.sub_services.items.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.sub_services.items.*.title' => ['nullable', 'string', 'max:200'],
            'content.sub_services.items.*.description' => ['nullable', 'string', 'max:1000'],

            // Section D — Process stepper
            'content.process.enabled' => ['nullable', 'boolean'],
            'content.process.heading' => ['nullable', 'string', 'max:200'],
            'content.process.subtitle' => ['nullable', 'string', 'max:500'],
            'content.process.steps' => ['nullable', 'array'],
            'content.process.steps.*.icon' => ['nullable', 'string', 'max:255'],
            'content.process.steps.*.icon_file' => ['nullable', 'image', 'max:2048'],
            'content.process.steps.*.title' => ['nullable', 'string', 'max:200'],
            'content.process.steps.*.description' => ['nullable', 'string', 'max:1000'],

            // Section E — Technologies (items via pivot)
            'content.tech.enabled' => ['nullable', 'boolean'],
            'content.tech.heading' => ['nullable', 'string', 'max:200'],
            'content.tech.subtitle' => ['nullable', 'string', 'max:500'],

            // Section F — Industries (items via pivot)
            'content.industries.enabled' => ['nullable', 'boolean'],
            'content.industries.heading' => ['nullable', 'string', 'max:200'],
            'content.industries.subtitle' => ['nullable', 'string', 'max:500'],

            // Section G — Case studies (items = latest active globally)
            'content.case_studies.enabled' => ['nullable', 'boolean'],
            'content.case_studies.heading' => ['nullable', 'string', 'max:200'],
            'content.case_studies.subtitle' => ['nullable', 'string', 'max:500'],

            // Section H — Why choose
            'content.why_choose.enabled' => ['nullable', 'boolean'],
            'content.why_choose.heading' => ['nullable', 'string', 'max:200'],
            'content.why_choose.subtitle' => ['nullable', 'string', 'max:500'],
            'content.why_choose.image' => ['nullable', 'string', 'max:255'],
            'content.why_choose.image_file' => ['nullable', 'image', 'max:4096'],
            'content.why_choose.cta_label' => ['nullable', 'string', 'max:60'],
            'content.why_choose.points' => ['nullable', 'array'],
            'content.why_choose.points.*' => ['nullable', 'string', 'max:200'],

            // Section I — FAQ accordion
            'content.faq.enabled' => ['nullable', 'boolean'],
            'content.faq.heading' => ['nullable', 'string', 'max:200'],
            'content.faq.subtitle' => ['nullable', 'string', 'max:500'],
            'content.faq.items' => ['nullable', 'array'],
            'content.faq.items.*.question' => ['nullable', 'string', 'max:300'],
            'content.faq.items.*.answer' => ['nullable', 'string', 'max:2000'],

            // Section J — CTA band
            'content.cta_band.enabled' => ['nullable', 'boolean'],
            'content.cta_band.heading' => ['nullable', 'string', 'max:200'],
            'content.cta_band.button_label' => ['nullable', 'string', 'max:60'],
        ];
    }
}
