<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesJobOpeningData;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOpeningRequest extends FormRequest
{
    use ValidatesJobOpeningData;

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return $this->jobOpeningRules($this->route('jobOpening'));
    }
}
