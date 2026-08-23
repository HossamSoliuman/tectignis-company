<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOemFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('oem_followup'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'oem_name' => ['required', 'string', 'max:255'],
            'requirement_type' => ['required', Rule::enum(OemRequirementType::class)],
            'product' => ['nullable', 'string', 'max:255'],
            'requested_on' => ['nullable', 'date'],
            'required_by' => ['nullable', 'date'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_channel' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(OemFollowupStatus::class)],
            'next_followup_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
