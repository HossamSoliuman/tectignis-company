<?php

namespace App\Http\Requests\Admin;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Support\AdminPagination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Search, filter and sort parameters for the Leads list and CSV export
 * (spec §26.6, §28.12: validated before they reach the query).
 */
class LeadFilterRequest extends FormRequest
{
    /**
     * Columns the list may be sorted by.
     *
     * @var list<string>
     */
    public const SORTABLE = ['created_at', 'name', 'company', 'country', 'status'];

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
            'status' => ['nullable', Rule::enum(LeadStatus::class)],
            'service' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'assigned_to' => ['nullable', 'regex:/^(unassigned|\d+)$/'],
            'source' => ['nullable', Rule::enum(LeadSource::class)],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort' => ['nullable', Rule::in(self::SORTABLE)],
            'direction' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', Rule::in(AdminPagination::PAGE_SIZES)],
            'trashed' => ['nullable', 'boolean'],
        ];
    }

    /**
     * The validated filters for {@see Lead::scopeFilter()}.
     *
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return collect($this->validated())
            ->only(['q', 'status', 'service', 'country', 'assigned_to', 'source', 'date_from', 'date_to'])
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

    /**
     * Only Super Admins may browse the trash.
     */
    public function wantsTrashed(): bool
    {
        return $this->boolean('trashed') && (bool) $this->user()?->isSuperAdmin();
    }
}
