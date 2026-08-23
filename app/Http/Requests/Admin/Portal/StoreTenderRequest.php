<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderPortalSource;
use App\Enums\Portal\TenderStage;
use App\Models\Portal\Tender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tender::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'tender_number' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'customer_organization' => ['nullable', 'string', 'max:255'],
            'portal' => ['required', Rule::enum(TenderPortalSource::class)],
            'tender_url' => ['nullable', 'url', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'pre_bid_at' => ['nullable', 'date'],
            'submission_start_at' => ['nullable', 'date'],
            'submission_deadline_at' => ['nullable', 'date', 'after_or_equal:submission_start_at'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'emd_required' => ['nullable', 'boolean'],
            'emd_amount' => ['nullable', 'numeric', 'min:0', 'required_if:emd_required,1'],
            'fee_required' => ['nullable', 'boolean'],
            'fee_amount' => ['nullable', 'numeric', 'min:0', 'required_if:fee_required,1'],
            'assigned_employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'technical_owner_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'sales_owner_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'stage' => ['required', Rule::enum(TenderStage::class)],
            'decision' => ['required', Rule::enum(TenderDecision::class)],
            'notes' => ['nullable', 'string'],
            'apply_task_template' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'emd_required' => $this->boolean('emd_required'),
            'fee_required' => $this->boolean('fee_required'),
        ]);
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'submission_deadline_at.after_or_equal' => 'The submission deadline cannot fall before submissions open.',
            'emd_amount.required_if' => 'State the EMD amount when EMD is required.',
            'fee_amount.required_if' => 'State the tender fee when a fee is required.',
        ];
    }
}
