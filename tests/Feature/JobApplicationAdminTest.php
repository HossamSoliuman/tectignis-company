<?php

use App\Enums\JobApplicationStatus;
use App\Enums\Portal\PortalRole;
use App\Enums\UserRole;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake(JobApplication::RESUME_DISK);
});

function hrUser(): User
{
    return User::factory()->role(UserRole::Hr)->create();
}

it('lets every careers role view applications', function (UserRole $role) {
    $application = JobApplication::factory()->create(['name' => 'Candidate Visible']);
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->get(route('admin.job-applications.index'))->assertOk()->assertSee('Candidate Visible');
    $this->actingAs($user)->get(route('admin.job-applications.show', $application))->assertOk();
})->with([UserRole::SuperAdmin, UserRole::Editor, UserRole::Hr]);

it('keeps sales, read-only, portal-only users and guests out of careers', function () {
    $application = JobApplication::factory()->create();

    $this->get(route('admin.job-applications.index'))->assertRedirect(route('login'));

    foreach ([User::factory()->role(UserRole::Sales)->create(), User::factory()->role(UserRole::ReadOnly)->create(), User::factory()->portal(PortalRole::Director)->create()] as $user) {
        $this->actingAs($user)->get(route('admin.job-applications.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.job-applications.show', $application))->assertForbidden();
        $this->actingAs($user)->get(route('admin.job-applications.resume', $application))->assertForbidden();
        $this->actingAs($user)->get(route('admin.job-openings.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.designations.index'))->assertForbidden();
    }
});

it('gives HR the careers module only, with no CMS or leads access', function () {
    $hr = hrUser();

    $this->actingAs($hr)->get(route('admin.dashboard'))->assertForbidden();
    $this->actingAs($hr)->get(route('admin.services.index'))->assertForbidden();
    $this->actingAs($hr)->get(route('admin.leads.index'))->assertForbidden();
    $this->actingAs($hr)->get(route('admin.job-openings.index'))->assertOk();
    $this->actingAs($hr)->get(route('admin.designations.index'))->assertOk();
    $this->actingAs($hr)->get(route('admin.careers-content.edit'))->assertOk();
    $this->actingAs($hr)->get(route('admin.job-applications.index'))
        ->assertOk()
        ->assertDontSee(route('admin.leads.index'))
        ->assertDontSee(route('admin.services.index'));
});

it('sends HR users to applications after signing in', function () {
    $hr = hrUser();

    $this->post(route('admin.login.store'), ['email' => $hr->email, 'password' => 'password'])
        ->assertRedirect(route('admin.job-applications.index'));
});

it('marks an application read when opened', function () {
    $application = JobApplication::factory()->create();

    $this->actingAs(hrUser())->get(route('admin.job-applications.show', $application))->assertOk();

    expect($application->fresh()->is_read)->toBeTrue();
});

it('moves an application through the pipeline with an audit trail', function () {
    $hr = hrUser();
    $recruiter = User::factory()->role(UserRole::Hr)->create(['name' => 'Rita Recruiter']);
    $application = JobApplication::factory()->create();

    $this->actingAs($hr)
        ->patch(route('admin.job-applications.update', $application), ['status' => 'shortlisted', 'assigned_to' => $recruiter->id])
        ->assertRedirect();

    $application->refresh();

    expect($application->status)->toBe(JobApplicationStatus::Shortlisted)
        ->and($application->assigned_to)->toBe($recruiter->id)
        ->and($application->activities->pluck('type')->all())->toContain('status_changed', 'assigned');

    $this->actingAs($hr)->get(route('admin.job-applications.show', $application))
        ->assertSee('Status changed from New to Shortlisted')
        ->assertSee('Assigned to Rita Recruiter');
});

it('only assigns applications to users with careers access', function () {
    $application = JobApplication::factory()->create();
    $sales = User::factory()->role(UserRole::Sales)->create();

    $this->actingAs(hrUser())
        ->patch(route('admin.job-applications.update', $application), ['status' => 'new', 'assigned_to' => $sales->id])
        ->assertSessionHasErrors('assigned_to');

    expect($application->fresh()->assigned_to)->toBeNull();
});

it('adds private notes', function () {
    $application = JobApplication::factory()->create();

    $this->actingAs(hrUser())
        ->post(route('admin.job-applications.notes.store', $application), ['body' => 'Strong Laravel background.'])
        ->assertRedirect();

    expect($application->notes()->sole()->body)->toBe('Strong Laravel background.');
});

it('searches by name, email, phone, job title and application ID', function (string $term) {
    $match = JobApplication::factory()->create(['name' => 'Arjun Mehta', 'email' => 'arjun@example.com', 'phone' => '9811122233', 'job_title' => 'DevOps Engineer']);
    JobApplication::factory()->create(['name' => 'Someone Else', 'email' => 'else@example.com', 'phone' => '9000000000', 'job_title' => 'Designer']);

    $term = str_replace('{id}', (string) $match->id, $term);

    $this->actingAs(hrUser())->get(route('admin.job-applications.index', ['q' => $term]))
        ->assertOk()
        ->assertSee('Arjun Mehta')
        ->assertDontSee('Someone Else');
})->with(['Arjun', 'arjun@example', '98111', 'DevOps', 'A-0000{id}']);

it('filters by job, status, experience, location and date', function () {
    $job = JobOpening::factory()->create();
    $match = JobApplication::factory()->forJob($job)->status(JobApplicationStatus::Interview)->create([
        'name' => 'Filtered In', 'experience' => '3-5', 'location' => 'Navi Mumbai', 'created_at' => now()->subDays(2),
    ]);
    JobApplication::factory()->create(['name' => 'Wrong Job', 'experience' => '3-5', 'location' => 'Navi Mumbai']);
    JobApplication::factory()->forJob($job)->create(['name' => 'Wrong Status', 'experience' => '3-5', 'location' => 'Navi Mumbai']);
    JobApplication::factory()->forJob($job)->status(JobApplicationStatus::Interview)->create(['name' => 'Wrong Location', 'experience' => '3-5', 'location' => 'Delhi']);

    $this->actingAs(hrUser())->get(route('admin.job-applications.index', [
        'job_opening_id' => $job->id,
        'status' => 'interview',
        'experience' => '3-5',
        'location' => 'Mumbai',
        'date_from' => now()->subWeek()->toDateString(),
        'date_to' => now()->toDateString(),
    ]))
        ->assertOk()
        ->assertSee($match->name)
        ->assertDontSee('Wrong Job')
        ->assertDontSee('Wrong Status')
        ->assertDontSee('Wrong Location');
});

it('paginates applications and keeps filters in the page links', function () {
    JobApplication::factory()->count(12)->status(JobApplicationStatus::Screening)->create();

    $this->actingAs(hrUser())->get(route('admin.job-applications.index', ['status' => 'screening', 'per_page' => 10]))
        ->assertOk()
        ->assertSee('status=screening', false)
        ->assertViewHas('applications', fn ($paginator): bool => $paginator->count() === 10 && $paginator->total() === 12);
});

it('archives and restores applications outside the working list', function () {
    $hr = hrUser();
    $application = JobApplication::factory()->status(JobApplicationStatus::Rejected)->create(['name' => 'Archived Candidate']);

    $this->actingAs($hr)->patch(route('admin.job-applications.archive', $application))->assertRedirect(route('admin.job-applications.index'));

    expect($application->fresh()->isArchived())->toBeTrue();
    $this->actingAs($hr)->get(route('admin.job-applications.index'))->assertDontSee('Archived Candidate');
    $this->actingAs($hr)->get(route('admin.job-applications.index', ['archived' => 1]))->assertSee('Archived Candidate');

    $this->actingAs($hr)->patch(route('admin.job-applications.unarchive', $application))->assertRedirect();

    expect($application->fresh()->isArchived())->toBeFalse();
});

it('lets only super admins permanently delete archived applications and their CV', function () {
    Storage::disk(JobApplication::RESUME_DISK)->put('resumes/test/cv.pdf', '%PDF-1.4');
    $application = JobApplication::factory()->withResume()->archived()->create();
    $live = JobApplication::factory()->create();

    $this->actingAs(hrUser())->delete(route('admin.job-applications.destroy', $application))->assertForbidden();

    $admin = User::factory()->role(UserRole::SuperAdmin)->create();
    $this->actingAs($admin)->delete(route('admin.job-applications.destroy', $live))->assertForbidden();
    $this->actingAs($admin)->delete(route('admin.job-applications.destroy', $application))->assertRedirect();

    $this->assertModelMissing($application);
    Storage::disk(JobApplication::RESUME_DISK)->assertMissing('resumes/test/cv.pdf');
});

it('streams the CV only through the authorised download route', function () {
    Storage::disk(JobApplication::RESUME_DISK)->put('resumes/test/cv.pdf', '%PDF-1.4 test');
    $application = JobApplication::factory()->withResume()->create(['name' => 'Kiran Rao']);

    $response = $this->actingAs(hrUser())->get(route('admin.job-applications.resume', $application));

    $response->assertOk()
        ->assertDownload('kiran-rao-'.$application->reference().'.pdf')
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    expect($application->activities()->where('type', 'resume_downloaded')->exists())->toBeTrue();
});

it('returns 404 when the CV file is missing', function () {
    $application = JobApplication::factory()->withResume('resumes/missing.pdf')->create();

    $this->actingAs(hrUser())->get(route('admin.job-applications.resume', $application))->assertNotFound();
});

it('exports filtered applications only with the separate export permission', function () {
    JobApplication::factory()->status(JobApplicationStatus::Selected)->create(['name' => 'Exported Candidate', 'phone' => '=HYPERLINK("x")']);
    JobApplication::factory()->create(['name' => 'Other Candidate']);

    $this->actingAs(hrUser())->get(route('admin.job-applications.export'))->assertForbidden();

    $hrWithExport = User::factory()->role(UserRole::Hr)->canExportApplications()->create();
    $csv = $this->actingAs($hrWithExport)->get(route('admin.job-applications.export', ['status' => 'selected']))
        ->assertOk()
        ->streamedContent();

    expect($csv)
        ->toContain('Application ID')
        ->toContain('Exported Candidate')
        ->toContain("'=HYPERLINK")
        ->not->toContain('Other Candidate');
});

it('does not let lead export rights export candidates', function () {
    $editor = User::factory()->role(UserRole::Editor)->canExportLeads()->create();

    $this->actingAs($editor)->get(route('admin.job-applications.export'))->assertForbidden();
});

it('shows the original designation on historical applications', function () {
    $designation = Designation::factory()->create(['name' => 'Trainee']);
    $application = JobApplication::factory()->forDesignation($designation)->create();
    $designation->update(['name' => 'Graduate Engineer', 'is_active' => false]);

    $this->actingAs(hrUser())->get(route('admin.job-applications.show', $application))
        ->assertOk()
        ->assertSee('Trainee')
        ->assertSee('Graduate Engineer')
        ->assertSee('Inactive');
});

it('never lists applications among leads', function () {
    JobApplication::factory()->create(['name' => 'Only A Candidate']);
    Lead::factory()->create(['name' => 'Only A Lead']);
    $admin = User::factory()->role(UserRole::SuperAdmin)->create();

    $this->actingAs($admin)->get(route('admin.leads.index'))->assertSee('Only A Lead')->assertDontSee('Only A Candidate');
    $this->actingAs($admin)->get(route('admin.job-applications.index'))->assertSee('Only A Candidate')->assertDontSee('Only A Lead');
});
