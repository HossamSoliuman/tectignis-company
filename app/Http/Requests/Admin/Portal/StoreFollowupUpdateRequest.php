<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * One contact attempt appended to a follow-up history (§11). Nothing here
 * edits an earlier entry — every chase is a new row.
 */
class StoreFollowupUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('oem_followup'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'note' => ['required', 'string', 'max:2000'],
            'status' => ['nullable', Rule::enum(OemFollowupStatus::class)],
            'contacted_on' => ['nullable', 'date'],
            'next_followup_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'max:20480'],
        ];
    }
}
