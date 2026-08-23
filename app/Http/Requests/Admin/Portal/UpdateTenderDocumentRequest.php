<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\TenderDocumentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenderDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(TenderDocumentStatus::class)],
            'responsible_employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'required_by' => ['nullable', 'date'],
            'expires_on' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:20480'],
        ];
    }
}
