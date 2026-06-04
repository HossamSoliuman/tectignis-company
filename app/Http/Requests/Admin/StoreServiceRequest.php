<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesServiceData;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', 'unique:services,slug'],
        ], $this->baseServiceRules());
    }
}
