<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\ClarificationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenderClarificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'response' => ['nullable', 'string', 'max:5000'],
            'response_on' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(ClarificationStatus::class)],
            'attachment' => ['nullable', 'file', 'max:20480'],
        ];
    }
}
