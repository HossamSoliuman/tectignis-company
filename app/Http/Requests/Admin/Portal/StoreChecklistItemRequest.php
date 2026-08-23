<?php

namespace App\Http\Requests\Admin\Portal;

use Illuminate\Foundation\Http\FormRequest;

class StoreChecklistItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'requirement' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'responsible_employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
