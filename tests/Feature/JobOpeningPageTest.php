<?php

use App\Enums\JobOpeningStatus;
use App\Enums\UserRole;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates a unique slug from the title', function () {
    $first = JobOpening::factory()->create(['title' => 'Laravel Developer']);
    $second = JobOpening::factory()->create(['title' => 'Laravel Developer']);

    expect($first->slug)->toBe('laravel-developer')
        ->and($second->slug)->toBe('laravel-developer-2');
});

it('stamps the publish date the first time a posting is published', function () {
    $job = JobOpening::factory()->draft()->create();
    expect($job->published_at)->toBeNull();

    $job->update(['status' => JobOpeningStatus::Published]);

    expect($job->fresh()->published_at)->not->toBeNull();
});

it('shows a published posting with its content, form and JobPosting schema', function () {
    Designation::factory()->create(['name' => 'Backend Developer']);
    $job = JobOpening::factory()->create([
        'title' => 'Laravel Developer',
        'description' => '<p>Build client platforms.</p>',
        'responsibilities' => "Ship features\nReview code",
        'skills' => 'Laravel, Vue',
        'salary' => '₹8–12 LPA',
        'show_salary' => true,
        'seo_title' => 'Laravel Developer Job in Navi Mumbai',
    ]);

    $this->get(route('careers.show', $job))
        ->assertOk()
        ->assertSee('Laravel Developer Job in Navi Mumbai')
        ->assertSee('Build client platforms.', false)
        ->assertSee('Ship features')
        ->assertSee('Vue')
        ->assertSee('₹8–12 LPA')
        ->assertSee('"@type":"JobPosting"', false)
        ->assertSee('name="job_opening_id" value="'.$job->id.'"', false)
        ->assertSee('Backend Developer');
});

it('hides the salary until it is approved for the public page', function () {
    $job = JobOpening::factory()->create(['salary' => '₹8–12 LPA', 'show_salary' => false]);

    $this->get(route('careers.show', $job))->assertOk()->assertDontSee('₹8–12 LPA');
});

it('keeps closed postings readable without the form, schema or indexing', function () {
    $job = JobOpening::factory()->closed()->create();

    $this->get(route('careers.show', $job))
        ->assertOk()
        ->assertSee('no longer accepting applications')
        ->assertSee('noindex', false)
        ->assertDontSee('JobPosting')
        ->assertDontSee('name="job_opening_id"', false);
});

it('treats a passed deadline as closed', function () {
    $job = JobOpening::factory()->create(['application_deadline' => now()->subDay()]);

    $this->get(route('careers.show', $job))->assertOk()->assertSee('no longer accepting applications');
    $this->get(route('careers'))->assertDontSee($job->title);
});

it('does not publish drafts', function () {
    $job = JobOpening::factory()->draft()->create();

    $this->get(route('careers.show', $job))->assertNotFound();
});

it('lists open postings in the sitemap only', function () {
    $open = JobOpening::factory()->create();
    $closed = JobOpening::factory()->closed()->create();
    $draft = JobOpening::factory()->draft()->create();

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertSee('<loc>'.route('careers.show', $open).'</loc>', false)
        ->assertDontSee('<loc>'.route('careers.show', $closed).'</loc>', false)
        ->assertDontSee('<loc>'.route('careers.show', $draft).'</loc>', false);
});

it('validates the required posting fields', function () {
    $this->actingAs(User::factory()->role(UserRole::Hr)->create())
        ->post(route('admin.job-openings.store'), ['title' => 'Incomplete'])
        ->assertSessionHasErrors(['department', 'location', 'experience', 'skills', 'status', 'employment_type']);
});

it('rejects a slug already used by another posting', function () {
    JobOpening::factory()->create(['slug' => 'taken-slug']);

    $this->actingAs(User::factory()->role(UserRole::Hr)->create())
        ->post(route('admin.job-openings.store'), [
            'title' => 'New Role',
            'slug' => 'taken-slug',
            'department' => 'Development',
            'location' => 'Remote',
            'employment_type' => 'Full-time',
            'experience' => '2 years',
            'skills' => 'PHP',
            'status' => 'draft',
        ])
        ->assertSessionHasErrors('slug');
});

it('does not delete an opening that has applications', function () {
    $job = JobOpening::factory()->create();
    JobApplication::factory()->forJob($job)->create();

    $this->actingAs(User::factory()->role(UserRole::Hr)->create())
        ->delete(route('admin.job-openings.destroy', $job))
        ->assertSessionHasErrors('job_opening');

    $this->assertModelExists($job);
});

it('filters job openings by status in the admin', function () {
    JobOpening::factory()->create(['title' => 'Live Role']);
    JobOpening::factory()->draft()->create(['title' => 'Drafted Role']);

    $this->actingAs(User::factory()->role(UserRole::Hr)->create())
        ->get(route('admin.job-openings.index', ['status' => 'draft']))
        ->assertOk()
        ->assertSee('Drafted Role')
        ->assertDontSee('Live Role');
});
