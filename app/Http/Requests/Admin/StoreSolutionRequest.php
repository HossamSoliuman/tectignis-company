<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesSolutionData;
use Illuminate\Foundation\Http\FormRequest;

class StoreSolutionRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', 'unique:solutions,slug'],
        ], $this->baseSolutionRules());
    }
}
