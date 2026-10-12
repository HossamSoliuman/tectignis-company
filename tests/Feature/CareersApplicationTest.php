<?php

use App\Enums\JobApplicationStatus;
use App\Mail\JobApplicationAcknowledgementMail;
use App\Mail\JobApplicationNotificationMail;
use App\Mail\LeadNotificationMail;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake(JobApplication::RESUME_DISK);
    Setting::set('mail_to_career', 'hr@tectignis.example', 'mail');
    Setting::set('mail_to_contact', 'sales@tectignis.example', 'mail');
});

/**
 * A complete, valid careers application (spec §26.10).
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validApplication(array $overrides = []): array
{
    return array_merge([
        'designation_id' => Designation::factory()->create()->id,
        'name' => 'Priya Sharma',
        'email' => 'priya@example.com',
        'phone' => '+91 98765 43210',
        'location' => 'Pune, India',
        'experience' => '3-5',
        'current_role' => 'Developer at Acme',
        'notice_period' => '30 days',
        'linkedin_url' => 'https://linkedin.com/in/priya',
        'portfolio_url' => 'https://github.com/priya',
        'cover_letter' => 'I would love to build products with your team.',
        'resume' => UploadedFile::fake()->create('Priya CV.pdf', 200, 'application/pdf'),
        'consent' => '1',
    ], $overrides);
}

it('stores an application against the chosen vacancy and designation', function () {
    Mail::fake();
    $job = JobOpening::factory()->create(['title' => 'Laravel Developer']);
    $data = validApplication(['job_opening_id' => $job->id]);

    $this->from(route('careers.show', $job))
        ->post(route('careers.submit'), $data)
        ->assertRedirect(route('careers.show', $job))
        ->assertSessionHas('career_status');

    $application = JobApplication::sole();

    expect($application->job_opening_id)->toBe($job->id)
        ->and($application->job_title)->toBe('Laravel Developer')
        ->and($application->designation_id)->toBe($data['designation_id'])
        ->and($application->designation_name)->toBe(Designation::find($data['designation_id'])->name)
        ->and($application->status)->toBe(JobApplicationStatus::New)
        ->and($application->experience)->toBe('3-5')
        ->and($application->consented_at)->not->toBeNull()
        ->and($application->resume_name)->toBe('Priya CV.pdf')
        ->and($application->activities()->first()->type)->toBe('created');
});

it('accepts a general application without a vacancy', function () {
    Mail::fake();

    $this->post(route('careers.submit'), validApplication())->assertSessionHasNoErrors();

    expect(JobApplication::sole())
        ->job_opening_id->toBeNull()
        ->appliedFor()->toBe('General application');
});

it('never creates a lead for a job application', function () {
    Mail::fake();

    $this->post(route('careers.submit'), validApplication())->assertSessionHasNoErrors();

    expect(Lead::count())->toBe(0)
        ->and(JobApplication::count())->toBe(1);

    Mail::assertNotSent(LeadNotificationMail::class);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.leads.index'))
        ->assertOk()
        ->assertDontSee('Priya Sharma');
});

it('stores the CV privately under an unguessable name', function () {
    Mail::fake();

    $this->post(route('careers.submit'), validApplication())->assertSessionHasNoErrors();

    $application = JobApplication::sole();

    Storage::disk(JobApplication::RESUME_DISK)->assertExists($application->resume_path);
    expect($application->resume_path)
        ->toStartWith('resumes/')
        ->toEndWith('.pdf')
        ->not->toContain('priya');
});

it('rejects CVs that are not PDF or Word documents', function (UploadedFile $file) {
    $this->post(route('careers.submit'), validApplication(['resume' => $file]))
        ->assertSessionHasErrorsIn('application', 'resume');

    expect(JobApplication::count())->toBe(0);
})->with([
    'image' => fn () => UploadedFile::fake()->image('photo.png'),
    'php script' => fn () => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
    'pdf extension with html content' => fn () => UploadedFile::fake()->create('cv.pdf', 10, 'text/html'),
    'too large' => fn () => UploadedFile::fake()->create('cv.pdf', 6000, 'application/pdf'),
]);

it('requires the core candidate fields', function () {
    $this->post(route('careers.submit'), [])
        ->assertSessionHasErrorsIn('application', ['designation_id', 'name', 'email', 'phone', 'location', 'experience', 'resume', 'consent']);
});

it('only accepts active designations', function () {
    $inactive = Designation::factory()->inactive()->create();

    $this->post(route('careers.submit'), validApplication(['designation_id' => $inactive->id]))
        ->assertSessionHasErrorsIn('application', 'designation_id');

    $this->post(route('careers.submit'), validApplication(['designation_id' => 999999]))
        ->assertSessionHasErrorsIn('application', 'designation_id');

    expect(JobApplication::count())->toBe(0);
});

it('rejects applications for drafts, closed postings and passed deadlines', function (JobOpening $job) {
    $this->post(route('careers.submit'), validApplication(['job_opening_id' => $job->id]))
        ->assertSessionHasErrorsIn('application', 'job_opening_id');

    expect(JobApplication::count())->toBe(0);
})->with([
    'draft' => fn () => JobOpening::factory()->draft()->create(),
    'closed' => fn () => JobOpening::factory()->closed()->create(),
    'deadline passed' => fn () => JobOpening::factory()->create(['application_deadline' => now()->subDay()]),
]);

it('keeps the original designation name after the designation is renamed or deactivated', function () {
    $designation = Designation::factory()->create(['name' => 'Junior Developer']);
    $application = JobApplication::factory()->forDesignation($designation)->create();

    $designation->update(['name' => 'Associate Engineer', 'is_active' => false]);

    expect($application->fresh()->designation_name)->toBe('Junior Developer')
        ->and($application->fresh()->designation->is($designation))->toBeTrue();
});

it('emails HR and acknowledges the candidate with separate careers templates', function () {
    Mail::fake();
    $job = JobOpening::factory()->create(['title' => 'Laravel Developer']);

    $this->post(route('careers.submit'), validApplication(['job_opening_id' => $job->id]))->assertSessionHasNoErrors();

    Mail::assertSent(JobApplicationNotificationMail::class, function (JobApplicationNotificationMail $mail): bool {
        return $mail->hasTo('hr@tectignis.example')
            && ! $mail->hasTo('sales@tectignis.example')
            && $mail->hasReplyTo('priya@example.com')
            && $mail->subjectLine() === 'New Job Application – Laravel Developer – Priya Sharma'
            && $mail->attachments === [];
    });

    Mail::assertSent(JobApplicationAcknowledgementMail::class, fn (JobApplicationAcknowledgementMail $mail): bool => $mail->hasTo('priya@example.com'));
});

it('skips the candidate acknowledgement when it is switched off', function () {
    Mail::fake();
    Setting::set('application_acknowledgement_enabled', '0', 'mail');

    $this->post(route('careers.submit'), validApplication())->assertSessionHasNoErrors();

    Mail::assertSent(JobApplicationNotificationMail::class);
    Mail::assertNotSent(JobApplicationAcknowledgementMail::class);
});

it('keeps the application and logs the error when mail fails', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Connection refused'));
    Log::spy();

    $this->post(route('careers.submit'), validApplication())->assertSessionHasNoErrors();

    expect(JobApplication::count())->toBe(1);

    Log::shouldHaveReceived('error')
        ->withArgs(fn (string $message, array $context): bool => str_contains($message, 'JobApplicationNotificationMail') && $context['job_application_id'] === JobApplication::sole()->id)
        ->once();
});

it('renders the notification with candidate details and an admin link but no CV link', function () {
    $application = JobApplication::factory()->withResume()->create(['job_title' => 'Laravel Developer', 'current_role' => 'Team Lead']);

    $html = (new JobApplicationNotificationMail($application))->render();

    expect($html)
        ->toContain('Laravel Developer')
        ->toContain('Team Lead')
        ->toContain(route('admin.job-applications.show', $application))
        ->not->toContain($application->resume_path);
});

it('shows the designation dropdown with active designations in display order', function () {
    Designation::factory()->create(['name' => 'Zeta Role', 'sort_order' => 1]);
    Designation::factory()->create(['name' => 'Alpha Role', 'sort_order' => 2]);
    Designation::factory()->inactive()->create(['name' => 'Retired Role']);

    $this->get(route('careers'))
        ->assertOk()
        ->assertSeeInOrder(['Select designation', 'Zeta Role', 'Alpha Role'])
        ->assertDontSee('Retired Role');
});

it('shows an empty state instead of the form when no designations are active', function () {
    Designation::factory()->inactive()->create();

    $this->get(route('careers'))
        ->assertOk()
        ->assertSee('No positions or designations are available')
        ->assertDontSee('name="designation_id"', false);
});
