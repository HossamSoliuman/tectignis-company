<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'note' => ['required', 'string', 'max:2000'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
