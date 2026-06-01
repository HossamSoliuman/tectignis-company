<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'from_path' => ['required', 'string', 'max:255', 'unique:redirects,from_path'],
            'to_path' => ['required', 'string', 'max:255'],
            'status_code' => ['nullable', 'integer', 'in:301,302'],
            'is_active' => ['boolean'],
        ];
    }
}
