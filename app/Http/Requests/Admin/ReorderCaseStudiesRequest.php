<?php

namespace App\Http\Requests\Admin;

use App\Support\AdminPagination;
use Illuminate\Foundation\Http\FormRequest;

class ReorderCaseStudiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'max:'.max(AdminPagination::PAGE_SIZES)],
            'ids.*' => ['integer', 'distinct', 'exists:case_studies,id'],
        ];
    }
}
