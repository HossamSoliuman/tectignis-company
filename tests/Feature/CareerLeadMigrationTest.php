<?php

use App\Enums\JobApplicationStatus;
use App\Enums\UserRole;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake(JobApplication::RESUME_DISK);
    File::ensureDirectoryExists(public_path('uploads/resumes'));
});

afterEach(function () {
    foreach (['jane-doe-test1234.pdf', 'orphan-test5678.pdf'] as $file) {
        @unlink(public_path('uploads/resumes/'.$file));
    }
});

function runCareerLeadMigration(): void
{
    (require database_path('migrations/2026_10_12_122332_move_career_leads_to_job_applications.php'))->up();
}

it('moves career leads, their notes and CVs into the careers module', function () {
    file_put_contents(public_path('uploads/resumes/jane-doe-test1234.pdf'), '%PDF-1.4 legacy');
    $job = JobOpening::factory()->create(['title' => 'Senior Software Developer']);
    $hr = User::factory()->role(UserRole::Hr)->create();
    $lead = Lead::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '9876543210',
        'source' => 'career',
        'subject' => 'Application: Senior Software Developer',
        'message' => "Total Experience: 3–5 years\nCurrent Location: Mumbai\nNotice Period: 30 days\n\nLooking forward to joining.",
        'attachment' => 'resumes/jane-doe-test1234.pdf',
        'status' => 'contacted',
        'assigned_to' => $hr->id,
        'is_read' => true,
    ]);
    LeadNote::factory()->create(['lead_id' => $lead->id, 'body' => 'Called her on Monday.']);
    $enquiry = Lead::factory()->create(['source' => 'contact']);

    runCareerLeadMigration();

    $application = JobApplication::sole();

    expect($application)
        ->name->toBe('Jane Doe')
        ->email->toBe('jane@example.com')
        ->job_opening_id->toBe($job->id)
        ->job_title->toBe('Senior Software Developer')
        ->experience->toBe('3-5')
        ->location->toBe('Mumbai')
        ->notice_period->toBe('30 days')
        ->cover_letter->toBe('Looking forward to joining.')
        ->status->toBe(JobApplicationStatus::Screening)
        ->assigned_to->toBe($hr->id)
        ->is_read->toBeTrue()
        ->resume_name->toBe('jane-doe-test1234.pdf')
        ->and($application->notes()->sole()->body)->toBe('Called her on Monday.')
        ->and($application->activities()->sole()->type)->toBe('imported');

    Storage::disk(JobApplication::RESUME_DISK)->assertExists($application->resume_path);
    expect($application->resume_path)->toStartWith('resumes/imported/')
        ->and(file_exists(public_path('uploads/resumes/jane-doe-test1234.pdf')))->toBeFalse()
        ->and(Lead::withTrashed()->where('source', 'career')->exists())->toBeFalse();

    $this->assertModelExists($enquiry);
});

it('archives trashed and spam career leads and drops non-careers assignees', function () {
    $sales = User::factory()->role(UserRole::Sales)->create();
    $trashed = Lead::factory()->create(['source' => 'career', 'subject' => 'Job Application', 'message' => 'Job application submitted from the careers page.', 'assigned_to' => $sales->id]);
    $trashed->delete();
    Lead::factory()->create(['source' => 'career', 'subject' => 'Job Application', 'message' => 'Job application submitted from the careers page.', 'status' => 'spam']);

    runCareerLeadMigration();

    expect(JobApplication::count())->toBe(2)
        ->and(JobApplication::archived()->count())->toBe(2)
        ->and(JobApplication::whereNotNull('assigned_to')->exists())->toBeFalse()
        ->and(JobApplication::whereNotNull('cover_letter')->exists())->toBeFalse()
        ->and(JobApplication::whereNotNull('job_title')->exists())->toBeFalse();
});

it('moves CVs no lead points at off the public folder', function () {
    file_put_contents(public_path('uploads/resumes/orphan-test5678.pdf'), '%PDF-1.4 orphan');

    runCareerLeadMigration();

    Storage::disk(JobApplication::RESUME_DISK)->assertExists('resumes/unlinked/orphan-test5678.pdf');
    expect(file_exists(public_path('uploads/resumes/orphan-test5678.pdf')))->toBeFalse();
});
