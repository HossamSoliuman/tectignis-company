<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\EligibilityStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Overruling the checklist always costs an explanation (§9) — the reason is
 * required, not optional, so the audit trail records why we bid anyway.
 */
class OverrideEligibilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('overrideEligibility', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'eligibility_status' => ['required', Rule::enum(EligibilityStatus::class)],
            'eligibility_override_reason' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'eligibility_override_reason.required' => 'Record why the checklist verdict is being overridden.',
            'eligibility_override_reason.min' => 'Give a real justification, not a placeholder.',
        ];
    }
}
