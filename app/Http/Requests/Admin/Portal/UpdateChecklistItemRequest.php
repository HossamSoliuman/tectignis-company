<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChecklistItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ChecklistItemStatus::class)],
            'responsible_employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
