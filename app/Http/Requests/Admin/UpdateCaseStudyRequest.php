<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesCaseStudyData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseStudyRequest extends FormRequest
{
    use ValidatesCaseStudyData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            ...$this->baseCaseStudyRules(),
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('case_studies', 'slug')->ignore($this->route('case_study'))],
        ];
    }
}
