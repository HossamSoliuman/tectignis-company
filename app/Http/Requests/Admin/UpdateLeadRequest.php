<?php

namespace App\Http\Requests\Admin;

use App\Enums\LeadStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('update', $this->route('lead'));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(LeadStatus::class)],
            'assigned_to' => ['nullable', 'integer', Rule::in(User::leadAssignees()->pluck('id')->all())],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'assigned_to.in' => 'Leads can only be assigned to users who can work leads.',
        ];
    }
}
