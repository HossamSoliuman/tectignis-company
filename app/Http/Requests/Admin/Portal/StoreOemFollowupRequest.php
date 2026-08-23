<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Models\Portal\OemFollowup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOemFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', OemFollowup::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'tender_id' => ['nullable', 'integer', 'exists:portal_tenders,id'],
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
