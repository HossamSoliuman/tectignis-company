<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage-users');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['nullable', 'string', Password::min(8), 'confirmed'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'can_export_leads' => ['nullable', 'boolean'],
        ];
    }

    /**
     * A Super Admin may not demote themselves and lock everyone out.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $editingSelf = $this->route('user')?->is($this->user());

                if ($editingSelf && $this->input('role') !== UserRole::SuperAdmin->value) {
                    $validator->errors()->add('role', 'You cannot remove your own Super Admin role.');
                }
            },
        ];
    }
}
