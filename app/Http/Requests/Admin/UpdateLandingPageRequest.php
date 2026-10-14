<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesLandingPageData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLandingPageRequest extends FormRequest
{
    use ValidatesLandingPageData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_merge([
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('landing_pages', 'slug')->ignore($this->route('landing_page'))],
        ], $this->baseLandingPageRules());
    }
}
