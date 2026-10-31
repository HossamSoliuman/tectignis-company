<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\LandingPage;
use App\Support\EnquiryServices;
use Illuminate\Validation\Rule;

/**
 * Rules shared by the landing page create and update forms.
 */
trait ValidatesLandingPageData
{
    /**
     * Drop project example rows left on "— Select —", keeping the picked
     * order, so a blank row neither fails validation nor counts to the limit.
     */
    protected function prepareForValidation(): void
    {
        $ids = $this->input('content.case_studies.ids');

        if (is_array($ids)) {
            $content = $this->input('content');
            $content['case_studies']['ids'] = array_values(array_filter($ids, fn (mixed $id): bool => filled($id)));

            $this->merge(['content' => $content]);
        }
    }

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
            'content.case_studies.heading' => ['nullable', 'string', 'max:255'],
            'content.case_studies.ids' => ['nullable', 'array', 'max:'.LandingPage::MAX_CASE_STUDIES],
            'content.case_studies.ids.*' => ['integer', 'distinct', Rule::exists('case_studies', 'id')],
            'content.cta.heading' => ['nullable', 'string', 'max:255'],
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
            'content.case_studies.ids' => 'project examples',
            'content.case_studies.ids.*' => 'project example',
        ];
    }
}
