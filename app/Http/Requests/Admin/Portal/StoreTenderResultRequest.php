<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\TenderOutcome;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenderResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('recordResult', $this->route('tender'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'technical_result' => ['nullable', 'string', 'max:255'],
            'financial_result' => ['nullable', 'string', 'max:255'],
            'outcome' => ['required', Rule::enum(TenderOutcome::class)],
            'awarded_value' => ['nullable', 'numeric', 'min:0', 'required_if:outcome,'.TenderOutcome::Won->value],
            'remarks' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:20480'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'awarded_value.required_if' => 'Record the awarded value for a won tender.',
        ];
    }
}
