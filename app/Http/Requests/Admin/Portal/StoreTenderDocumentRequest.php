<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\TenderDocumentCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenderDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(TenderDocumentCategory::class)],
            'is_required' => ['nullable', 'boolean'],
            'responsible_employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'required_by' => ['nullable', 'date'],
            'expires_on' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_required' => $this->boolean('is_required')]);
    }
}
