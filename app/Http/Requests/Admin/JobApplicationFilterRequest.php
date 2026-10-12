<?php

namespace App\Http\Requests\Admin;

use App\Enums\CandidateExperience;
use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Support\AdminPagination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Search, filter and sort parameters for the Applications list and CSV
 * export (spec §26.8, §28.12: validated before they reach the query).
 */
class JobApplicationFilterRequest extends FormRequest
{
    /**
     * Columns the list may be sorted by.
     *
     * @var list<string>
     */
    public const SORTABLE = ['created_at', 'name', 'job_title', 'experience', 'status'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(JobApplicationStatus::class)],
            'job_opening_id' => ['nullable', 'regex:/^(general|\d+)$/'],
            'designation_id' => ['nullable', 'integer'],
            'experience' => ['nullable', Rule::enum(CandidateExperience::class)],
            'location' => ['nullable', 'string', 'max:100'],
            'assigned_to' => ['nullable', 'regex:/^(unassigned|\d+)$/'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort' => ['nullable', Rule::in(self::SORTABLE)],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', Rule::in(AdminPagination::PAGE_SIZES)],
            'archived' => ['nullable', 'boolean'],
        ];
    }

    /**
     * The validated filters for {@see JobApplication::scopeFilter()}.
     *
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return collect($this->validated())
            ->only(['q', 'status', 'job_opening_id', 'designation_id', 'experience', 'location', 'assigned_to', 'date_from', 'date_to'])
            ->all();
    }

    public function sortColumn(): string
    {
        return $this->validated('sort') ?? 'created_at';
    }

    public function sortDirection(): string
    {
        return $this->validated('direction') ?? 'desc';
    }

    public function wantsArchived(): bool
    {
        return $this->boolean('archived');
    }
}
