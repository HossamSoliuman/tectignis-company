<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Employee::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:portal_employees,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'integer', 'exists:portal_departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'date_of_joining' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(EmployeeStatus::class)],
            'reports_to_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'notes' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer', 'exists:users,id', 'unique:portal_employees,user_id'],
            'portal_role' => ['nullable', Rule::enum(PortalRole::class)],
        ];
    }
}
