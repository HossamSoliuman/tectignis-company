<?php

namespace App\Http\Requests\Admin;

use App\Enums\JobApplicationStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('update', $this->route('jobApplication'));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(JobApplicationStatus::class)],
            'assigned_to' => ['nullable', 'integer', Rule::in(User::applicationAssignees()->pluck('id')->all())],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'assigned_to.in' => 'Applications can only be assigned to users with Careers access.',
        ];
    }
}
