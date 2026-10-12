<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Enums\JobOpeningStatus;
use App\Models\JobOpening;
use Illuminate\Validation\Rule;

trait ValidatesJobOpeningData
{
    /**
     * Rules shared by the store and update job opening requests (spec §26.9).
     * The slug is optional — it is generated from the title when left blank.
     *
     * @return array<string, array<int, mixed>>
     */
    protected function jobOpeningRules(?JobOpening $opening = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('job_openings', 'slug')->ignore($opening)],
            'department' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', Rule::in(JobOpening::EMPLOYMENT_TYPES)],
            'experience' => ['required', 'string', 'max:255'],
            'skills' => ['required', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65000'],
            'responsibilities' => ['nullable', 'string', 'max:65000'],
            'requirements' => ['nullable', 'string', 'max:65000'],
            'benefits' => ['nullable', 'string', 'max:65000'],
            'salary' => ['nullable', 'string', 'max:255'],
            'show_salary' => ['boolean'],
            'application_deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(JobOpeningStatus::class)],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * A blank slug means "generate one from the title".
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => filled($this->input('slug')) ? str($this->input('slug'))->slug()->toString() : null,
        ]);
    }
}
