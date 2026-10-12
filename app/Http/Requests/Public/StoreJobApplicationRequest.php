<?php

namespace App\Http\Requests\Public;

use App\Enums\CandidateExperience;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Rules\Recaptcha;
use App\Services\RecaptchaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * The careers application form (spec §26.10, §28.9). Errors go to their own
 * bag so they never open or decorate the enquiry forms on the same page.
 */
class StoreJobApplicationRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $errorBag = 'application';

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
            'job_opening_id' => ['nullable', 'integer', Rule::in(JobOpening::open()->pluck('id')->all())],
            'designation_id' => ['required', 'integer', Rule::exists(Designation::class, 'id')->where('is_active', true)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9][0-9\s().-]{5,23}$/'],
            'location' => ['required', 'string', 'max:255'],
            'experience' => ['required', Rule::enum(CandidateExperience::class)],
            'current_role' => ['nullable', 'string', 'max:255'],
            'notice_period' => ['nullable', Rule::in(JobApplication::NOTICE_PERIODS)],
            'linkedin_url' => ['nullable', 'url:http,https', 'max:500'],
            'portfolio_url' => ['nullable', 'url:http,https', 'max:500'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'resume' => ['required', File::types(['pdf', 'doc', 'docx'])->max(5 * 1024), 'extensions:pdf,doc,docx'],
            'consent' => ['accepted'],
            RecaptchaService::RESPONSE_FIELD => [new Recaptcha],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'job_opening_id' => 'position',
            'designation_id' => 'designation',
            'name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'location' => 'current location',
            'experience' => 'total experience',
            'current_role' => 'current role',
            'linkedin_url' => 'LinkedIn profile',
            'portfolio_url' => 'portfolio / GitHub link',
            'resume' => 'resume / CV',
            'consent' => 'privacy consent',
            RecaptchaService::RESPONSE_FIELD => 'CAPTCHA',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'job_opening_id.in' => 'This position is no longer accepting applications.',
            'designation_id.exists' => 'Please choose a designation from the list.',
            'phone.regex' => 'Please enter a valid phone number, e.g. 98765 43210.',
            'consent.accepted' => 'Please agree to the privacy policy so we can process your application.',
        ];
    }
}
