<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesSolutionData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSolutionRequest extends FormRequest
{
    use ValidatesSolutionData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_merge([
            'slug' => ['required', 'string', 'max:255', Rule::unique('solutions', 'slug')->ignore($this->route('solution'))],
        ], $this->baseSolutionRules());
    }
}
