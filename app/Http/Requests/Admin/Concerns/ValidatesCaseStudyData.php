<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Enums\CaseStudyStatus;
use App\Enums\ProjectStatus;
use Illuminate\Validation\Rule;

trait ValidatesCaseStudyData
{
    /**
     * Fields entered one item per line in the admin form.
     *
     * @var list<string>
     */
    private const LINE_LIST_FIELDS = ['features', 'modules', 'technologies'];

    /**
     * Turn the one-per-line textareas into arrays.
     */
    protected function prepareForValidation(): void
    {
        foreach (self::LINE_LIST_FIELDS as $field) {
            if (is_string($this->input($field))) {
                $this->merge([
                    $field => array_values(array_filter(
                        array_map('trim', preg_split('/\r\n|\r|\n/', $this->input($field))),
                        fn (string $line): bool => $line !== '',
                    )),
                ]);
            }
        }
    }

    /**
     * Rules shared by the store and update requests (everything except the
     * slug, whose uniqueness rule differs between create and update).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function baseCaseStudyRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(CaseStudyStatus::class)],
            'project_status' => ['required', Rule::enum(ProjectStatus::class)],
            'client_name' => ['nullable', 'string', 'max:255'],
            'case_study_category_id' => ['nullable', 'integer', 'exists:case_study_categories,id'],
            'industry_id' => ['nullable', 'integer', 'exists:industries,id'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'testimonial_id' => ['nullable', 'integer', 'exists:testimonials,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'project_date' => ['nullable', 'date'],
            'theme' => ['nullable', 'string', 'in:blue,red,purple'],
            'short_description' => ['required', 'string', 'max:500'],
            'challenge' => ['nullable', 'string', 'max:5000'],
            'solution' => ['nullable', 'string', 'max:5000'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', 'max:255'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:100'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'outcome' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:4096'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*.image' => ['required', 'string', 'max:255'],
            'gallery.*.alt' => ['nullable', 'string', 'max:255'],
            'gallery.*.remove' => ['nullable', 'boolean'],
            'gallery_uploads' => ['nullable', 'array', 'max:12'],
            'gallery_uploads.*' => ['file', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
