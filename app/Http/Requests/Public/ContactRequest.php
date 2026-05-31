<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'con_name' => ['required', 'string', 'max:150'],
            'con_email' => ['required', 'email', 'max:190'],
            'con_phone' => ['required', 'string', 'max:20'],
            'con_subject' => ['required', 'string', 'max:200'],
            'con_message' => ['required', 'string', 'max:5000'],
        ];
    }
}
