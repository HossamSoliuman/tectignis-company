<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Support\EnquiryServices;
use Illuminate\Validation\Rule;

/**
 * Rules shared by the landing page create and update forms.
 */
trait ValidatesLandingPageData
{
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function baseLandingPageRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')],
            'enquiry_service' => ['required', 'string', Rule::in(EnquiryServices::all())],
            'headline' => ['required', 'string', 'max:255'],
            'supporting_text' => ['nullable', 'string', 'max:1000'],
            'primary_cta_label' => ['required', 'string', 'max:60'],
            'secondary_cta_label' => ['required', 'string', 'max:60'],
            'content' => ['nullable', 'array'],
            'content.bullets' => ['nullable', 'array', 'max:8'],
            'content.bullets.*' => ['nullable', 'string', 'max:255'],
            'content.form.heading' => ['nullable', 'string', 'max:255'],
            'content.form.text' => ['nullable', 'string', 'max:500'],
            'content.faq.heading' => ['nullable', 'string', 'max:255'],
            'content.faq.items' => ['nullable', 'array', 'max:20'],
            'content.faq.items.*.question' => ['nullable', 'string', 'max:255'],
            'content.faq.items.*.answer' => ['nullable', 'string', 'max:2000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'service_id' => 'linked service',
            'enquiry_service' => 'form service',
            'primary_cta_label' => 'primary button label',
            'secondary_cta_label' => 'secondary button label',
        ];
    }
}
