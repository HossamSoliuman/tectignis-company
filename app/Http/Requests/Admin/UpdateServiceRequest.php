<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesServiceData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    use ValidatesServiceData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_merge([
            'slug' => ['required', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($this->route('service'))],
        ], $this->baseServiceRules());
    }
}
