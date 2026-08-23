<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employee'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('portal_employees', 'employee_code')->ignore($employee)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'integer', 'exists:portal_departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'date_of_joining' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(EmployeeStatus::class)],
            // An employee cannot be their own manager; deeper cycles are left to
            // the org chart to make obvious rather than blocked here.
            'reports_to_id' => ['nullable', 'integer', 'exists:portal_employees,id', Rule::notIn([$employee->id])],
            'notes' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer', 'exists:users,id', Rule::unique('portal_employees', 'user_id')->ignore($employee)],
            'portal_role' => ['nullable', Rule::enum(PortalRole::class)],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'reports_to_id.not_in' => 'An employee cannot report to themselves.',
        ];
    }
}
