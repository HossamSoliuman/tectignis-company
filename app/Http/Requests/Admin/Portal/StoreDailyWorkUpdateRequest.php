<?php

namespace App\Http\Requests\Admin\Portal;

use App\Enums\Portal\DailyWorkCategory;
use App\Enums\Portal\DailyWorkStatus;
use App\Models\Portal\DailyWorkUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDailyWorkUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DailyWorkUpdate::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            // Only management logs work on behalf of somebody else; the
            // controller ignores this field for everyone else.
            'employee_id' => ['nullable', 'integer', 'exists:portal_employees,id'],
            'work_date' => ['required', 'date', 'before_or_equal:today'],
            'related_category' => ['required', Rule::enum(DailyWorkCategory::class)],
            'related_task_id' => ['nullable', 'integer', 'exists:portal_tasks,id'],
            'activity' => ['required', 'string', 'max:2000'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['required', Rule::enum(DailyWorkStatus::class)],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'work_date.before_or_equal' => 'You cannot log work for a future date.',
            'end_time.after' => 'The end time must be later than the start time.',
        ];
    }
}
