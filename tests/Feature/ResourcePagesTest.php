<?php

use App\Models\BlogPost;
use App\Models\Download;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Insight;
use App\Models\JobOpening;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

function resourceAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/

it('blog index filters by category and search', function () {
    // Posts excluded by a filter still show in the "Popular Posts" sidebar,
    // so the assertions target the excerpts, which only render on the cards.
    BlogPost::factory()->create([
        'title' => 'Cloud Migration Guide',
        'category' => 'Cloud Computing',
        'excerpt' => 'Excerpt about cloud migration strategies.',
    ]);
    BlogPost::factory()->create([
        'title' => 'VAPT Essentials',
        'category' => 'Cyber Security',
        'excerpt' => 'Excerpt about penetration testing.',
    ]);

    $this->get(route('blog.index', ['category' => 'Cloud Computing']))
        ->assertOk()
        ->assertSee('Excerpt about cloud migration strategies.')
        ->assertDontSee('Excerpt about penetration testing.');

    $this->get(route('blog.index', ['q' => 'VAPT']))
        ->assertOk()
        ->assertSee('Excerpt about penetration testing.')
        ->assertDontSee('Excerpt about cloud migration strategies.');
});

it('technology insights index returns 200 and lists insights', function () {
    $insight = Insight::factory()->create();

    $this->get(route('technology-insights'))
        ->assertOk()
        ->assertSee($insight->title);
});

it('technology insights index filters by topic', function () {
    Insight::factory()->create(['title' => 'Cloud Trends 2025', 'topic' => 'Cloud Computing']);
    Insight::factory()->create(['title' => 'Zero Trust Explained', 'topic' => 'Cyber Security']);

    $this->get(route('technology-insights', ['topic' => 'Cloud Computing']))
        ->assertOk()
        ->assertSee('Cloud Trends 2025')
        ->assertDontSee('Zero Trust Explained');
});

it('insight show returns 200 for published insight and 404 for unpublished', function () {
    $published = Insight::factory()->create(['slug' => 'published-insight']);
    Insight::factory()->create(['slug' => 'draft-insight', 'is_published' => false]);

    $this->get(route('insights.show', $published->slug))->assertOk();
    $this->get(route('insights.show', 'draft-insight'))->assertNotFound();
});

it('downloads page returns 200 and lists active downloads only', function () {
    $active = Download::factory()->create(['title' => 'Company Profile Active']);
    Download::factory()->create(['title' => 'Hidden Resource', 'is_active' => false]);

    $this->get(route('downloads'))
        ->assertOk()
        ->assertSee($active->title)
        ->assertDontSee('Hidden Resource');
});

it('download request stores a lead and flashes the file url', function () {
    $download = Download::factory()->create(['file' => 'downloads/company-profile.pdf']);

    $this->from(route('downloads'))
        ->post(route('downloads.request'), [
            'download_id' => $download->id,
            'name' => 'Jane Doe',
            'phone' => '9876543210',
            'email' => 'jane@example.com',
        ])
        ->assertRedirect(route('downloads'))
        ->assertSessionHas('download_url');

    $this->assertDatabaseHas('leads', [
        'name' => 'Jane Doe',
        'source' => 'download',
        'subject' => 'Download: '.$download->title,
    ]);
});

it('download request requires name and phone', function () {
    $download = Download::factory()->create();

    $this->post(route('downloads.request'), ['download_id' => $download->id])
        ->assertSessionHasErrors(['name', 'phone']);
});

it('faqs page returns 200 and shows active faqs grouped by category', function () {
    $category = FaqCategory::factory()->create(['name' => 'Cloud Solutions']);
    $faq = Faq::factory()->for($category, 'category')->create();
    Faq::factory()->for($category, 'category')->create(['question' => 'Hidden question?', 'is_active' => false]);

    $this->get(route('faqs'))
        ->assertOk()
        ->assertSee('Cloud Solutions')
        ->assertSee($faq->question)
        ->assertDontSee('Hidden question?');
});

it('careers page lists active job openings only', function () {
    $job = JobOpening::factory()->create(['title' => 'Senior Software Developer']);
    JobOpening::factory()->create(['title' => 'Closed Position', 'is_active' => false]);

    $this->get(route('careers'))
        ->assertOk()
        ->assertSee($job->title)
        ->assertDontSee('Closed Position');
});

it('careers application stores a lead with resume attachment', function () {
    $this->from(route('careers'))
        ->post(route('careers.submit'), [
            'con_form' => 'career',
            'con_name' => 'John Applicant',
            'con_email' => 'john@example.com',
            'con_phone' => '9876543210',
            'con_position' => 'Senior Software Developer',
            'con_experience' => '3–5 years',
            'con_location' => 'Mumbai',
            'con_notice_period' => '30 days',
            'con_resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            'con_message' => 'Looking forward to joining.',
        ])
        ->assertRedirect(route('careers'))
        ->assertSessionHas('career_status');

    $lead = Lead::where('source', 'career')->first();

    expect($lead)->not->toBeNull()
        ->and($lead->subject)->toBe('Application: Senior Software Developer')
        ->and($lead->message)->toContain('Total Experience: 3–5 years')
        ->and($lead->attachment)->toStartWith('resumes/');

    @unlink(public_path('uploads/'.$lead->attachment));
});

it('careers application rejects non-document resumes', function () {
    $this->post(route('careers.submit'), [
        'con_name' => 'John Applicant',
        'con_email' => 'john@example.com',
        'con_phone' => '9876543210',
        'con_resume' => UploadedFile::fake()->image('photo.png'),
    ])->assertSessionHasErrors('con_resume');
});

it('newsletter subscription stores a lead', function () {
    $this->from(route('blog.index'))
        ->post(route('newsletter.subscribe'), ['email' => 'subscriber@example.com'])
        ->assertRedirect(route('blog.index'))
        ->assertSessionHas('newsletter_status');

    $this->assertDatabaseHas('leads', [
        'email' => 'subscriber@example.com',
        'source' => 'newsletter',
    ]);
});

/*
|--------------------------------------------------------------------------
| Admin CRUD
|--------------------------------------------------------------------------
*/

it('admin can create an insight', function () {
    $this->actingAs(resourceAdmin())
        ->post(route('admin.insights.store'), [
            'title' => 'New Insight',
            'topic' => 'Cloud Computing',
            'content' => '<p>Insight body</p>',
            'published_at' => now()->format('Y-m-d\TH:i'),
            'is_published' => '1',
        ])->assertRedirect(route('admin.insights.index'));

    $this->assertDatabaseHas('insights', ['slug' => 'new-insight', 'topic' => 'Cloud Computing']);
});

it('admin can update and delete an insight', function () {
    $insight = Insight::factory()->create();

    $this->actingAs(resourceAdmin())
        ->put(route('admin.insights.update', $insight), [
            'title' => 'Updated Insight',
            'slug' => $insight->slug,
            'content' => $insight->content,
            'is_published' => '1',
        ])->assertRedirect(route('admin.insights.index'));

    $this->assertDatabaseHas('insights', ['id' => $insight->id, 'title' => 'Updated Insight']);

    $this->actingAs(resourceAdmin())
        ->delete(route('admin.insights.destroy', $insight))
        ->assertRedirect(route('admin.insights.index'));

    $this->assertDatabaseMissing('insights', ['id' => $insight->id]);
});

it('admin can manage faq categories and faqs', function () {
    $admin = resourceAdmin();

    $this->actingAs($admin)
        ->post(route('admin.faq-categories.store'), [
            'name' => 'Cloud Solutions',
            'icon' => 'fas fa-cloud',
        ])->assertRedirect(route('admin.faq-categories.index'));

    $category = FaqCategory::where('name', 'Cloud Solutions')->first();
    expect($category)->not->toBeNull();

    $this->actingAs($admin)
        ->post(route('admin.faqs.store'), [
            'faq_category_id' => $category->id,
            'question' => 'How do cloud solutions help?',
            'answer' => 'They scale with your business.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.faqs.index'));

    $this->assertDatabaseHas('faqs', ['question' => 'How do cloud solutions help?']);

    // Deleting the category cascades to its FAQs.
    $this->actingAs($admin)
        ->delete(route('admin.faq-categories.destroy', $category))
        ->assertRedirect(route('admin.faq-categories.index'));

    $this->assertDatabaseMissing('faqs', ['question' => 'How do cloud solutions help?']);
});

it('admin can create a download with a file', function () {
    $this->actingAs(resourceAdmin())
        ->post(route('admin.downloads.store'), [
            'title' => 'Company Profile',
            'category' => 'brochure',
            'file_type' => 'pdf',
            'description' => 'Overview of Tectignis.',
            'file' => UploadedFile::fake()->create('company-profile.pdf', 200, 'application/pdf'),
            'is_active' => '1',
        ])->assertRedirect(route('admin.downloads.index'));

    $download = Download::where('title', 'Company Profile')->first();

    expect($download)->not->toBeNull()
        ->and($download->file)->toStartWith('downloads/');

    @unlink(public_path('uploads/'.$download->file));
});

it('admin can create update and delete a job opening', function () {
    $admin = resourceAdmin();

    $this->actingAs($admin)
        ->post(route('admin.job-openings.store'), [
            'title' => 'AI/ML Engineer',
            'department' => 'AI & Automation',
            'location' => 'Navi Mumbai, India',
            'employment_type' => 'Full-time',
            'is_active' => '1',
        ])->assertRedirect(route('admin.job-openings.index'));

    $job = JobOpening::where('title', 'AI/ML Engineer')->first();
    expect($job)->not->toBeNull();

    $this->actingAs($admin)
        ->put(route('admin.job-openings.update', $job), [
            'title' => 'Senior AI/ML Engineer',
            'employment_type' => 'Full-time',
            'is_active' => '1',
        ])->assertRedirect(route('admin.job-openings.index'));

    $this->assertDatabaseHas('job_openings', ['id' => $job->id, 'title' => 'Senior AI/ML Engineer']);

    $this->actingAs($admin)
        ->delete(route('admin.job-openings.destroy', $job))
        ->assertRedirect(route('admin.job-openings.index'));

    $this->assertDatabaseMissing('job_openings', ['id' => $job->id]);
});

it('guests cannot access the admin resource pages', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with([
    'insights' => 'admin.insights.index',
    'faqs' => 'admin.faqs.index',
    'faq categories' => 'admin.faq-categories.index',
    'downloads' => 'admin.downloads.index',
    'job openings' => 'admin.job-openings.index',
]);
