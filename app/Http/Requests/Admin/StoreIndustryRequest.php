<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesIndustryData;
use Illuminate\Foundation\Http\FormRequest;

class StoreIndustryRequest extends FormRequest
{
    use ValidatesIndustryData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_merge([
            'slug' => ['required', 'string', 'max:255', 'unique:industries,slug'],
        ], $this->baseIndustryRules());
    }
}
