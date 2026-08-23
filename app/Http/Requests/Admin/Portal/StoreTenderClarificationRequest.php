<?php

namespace App\Http\Requests\Admin\Portal;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenderClarificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:2000'],
            'raised_on' => ['nullable', 'date'],
            'submitted_through' => ['nullable', 'string', 'max:255'],
        ];
    }
}
