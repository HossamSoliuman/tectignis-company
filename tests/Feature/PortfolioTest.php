<?php

use App\Enums\CaseStudyStatus;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\PortfolioCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

function portfolioAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('lists only published projects on the public portfolio', function () {
    CaseStudy::factory()->create(['title' => 'Published Portal']);
    CaseStudy::factory()->draft()->create(['title' => 'Draft Portal']);
    CaseStudy::factory()->archived()->create(['title' => 'Archived Portal']);

    $this->get(route('case-studies.index'))
        ->assertOk()
        ->assertSee('Published Portal')
        ->assertDontSee('Draft Portal')
        ->assertDontSee('Archived Portal');
});

it('filters the portfolio by category and only offers categories with published projects', function () {
    $website = CaseStudyCategory::firstWhere('name', 'Website');
    $software = CaseStudyCategory::firstWhere('name', 'Software');
    $empty = CaseStudyCategory::firstWhere('name', 'Networking');
    CaseStudy::factory()->create(['title' => 'Shop Site', 'case_study_category_id' => $website->id]);
    CaseStudy::factory()->create(['title' => 'ERP Build', 'case_study_category_id' => $software->id]);
    CaseStudy::factory()->draft()->create(['case_study_category_id' => $empty->id]);

    $this->get(route('case-studies.index'))
        ->assertSee(route('case-studies.index', ['category' => $website->id]), false)
        ->assertDontSee(route('case-studies.index', ['category' => $empty->id]), false);

    $this->get(route('case-studies.index', ['category' => $software->id]))
        ->assertOk()
        ->assertSee('ERP Build')
        ->assertDontSee('Shop Site')
        ->assertSee('noindex, follow', false);
});

it('labels ongoing projects', function () {
    $project = CaseStudy::factory()->ongoing()->create(['title' => 'Live Rollout']);

    $this->get(route('case-studies.index'))->assertSee('Ongoing');
    $this->get(route('case-studies.show', $project->slug))->assertSee('Ongoing Project');
});

it('renders the full case study with its story, stack, outcome, testimonial and related service', function () {
    $service = Service::factory()->create(['title' => 'Custom Software Development', 'is_active' => true]);
    $testimonial = Testimonial::factory()->create(['name' => 'Ops Director', 'quote' => 'Delivered on schedule.', 'is_active' => true]);

    $project = CaseStudy::factory()->create([
        'title' => 'Fleet Portal',
        'client_name' => 'A logistics company',
        'location' => 'Pune, India',
        'challenge' => 'Dispatch ran on spreadsheets.',
        'solution' => 'A web portal with live tracking.',
        'modules' => ['Dispatch board', 'Driver app'],
        'technologies' => ['Laravel', 'MySQL'],
        'outcome' => 'Dispatch time halved, confirmed by the client.',
        'service_id' => $service->id,
        'testimonial_id' => $testimonial->id,
        'seo_title' => 'Fleet Portal Case Study',
    ]);

    $this->get(route('case-studies.show', $project->slug))
        ->assertOk()
        ->assertSee('<title>Fleet Portal Case Study</title>', false)
        ->assertSee('A logistics company')
        ->assertSee('Pune, India')
        ->assertSee('Dispatch ran on spreadsheets.')
        ->assertSee('A web portal with live tracking.')
        ->assertSee('Driver app')
        ->assertSee('Laravel')
        ->assertSee('Dispatch time halved, confirmed by the client.')
        ->assertSee('Delivered on schedule.')
        ->assertSee('Discuss a Similar Project')
        ->assertSee(localized_route('services.show', $service->slug), false);
});

it('hides drafts publicly but lets admins preview them', function () {
    $draft = CaseStudy::factory()->draft()->create(['title' => 'Secret Draft']);

    $this->get(route('case-studies.show', $draft->slug))->assertNotFound();

    $this->actingAs(portfolioAdmin())
        ->get(route('admin.case-studies.show', $draft))
        ->assertOk()
        ->assertSee('Secret Draft')
        ->assertSee('not visible on the public site')
        ->assertSee('noindex, nofollow', false);
});

it('creates a project with a unique slug, line lists and screenshots', function () {
    CaseStudy::factory()->create(['slug' => 'warehouse-app']);

    $this->actingAs(portfolioAdmin())
        ->post(route('admin.case-studies.store'), [
            'title' => 'Warehouse App',
            'short_description' => 'Stock and picking for a warehouse.',
            'status' => 'draft',
            'project_status' => 'ongoing',
            'modules' => "Inventory\nPicking\n",
            'technologies' => "Laravel\n\nVue",
            'is_featured' => '1',
            'gallery_uploads' => [UploadedFile::fake()->image('screen.png')],
        ])
        ->assertRedirect();

    $project = CaseStudy::firstWhere('title', 'Warehouse App');

    expect($project->slug)->toBe('warehouse-app-2')
        ->and($project->status)->toBe(CaseStudyStatus::Draft)
        ->and($project->isOngoing())->toBeTrue()
        ->and($project->modules)->toBe(['Inventory', 'Picking'])
        ->and($project->technologies)->toBe(['Laravel', 'Vue'])
        ->and($project->is_featured)->toBeTrue()
        ->and($project->gallery)->toHaveCount(1);

    $image = $project->gallery[0]['image'];
    expect(public_path('uploads/'.$image))->toBeFile();

    $this->actingAs(portfolioAdmin())
        ->put(route('admin.case-studies.update', $project), [
            'title' => 'Warehouse App',
            'short_description' => 'Stock and picking for a warehouse.',
            'status' => 'published',
            'project_status' => 'completed',
            'gallery' => [['image' => $image, 'alt' => 'Picking screen', 'remove' => '0']],
        ])
        ->assertRedirect(route('admin.case-studies.edit', $project));

    expect($project->refresh()->galleryImages())->toBe([['image' => $image, 'alt' => 'Picking screen']])
        ->and($project->isPublished())->toBeTrue();

    $this->actingAs(portfolioAdmin())
        ->put(route('admin.case-studies.update', $project), [
            'title' => 'Warehouse App',
            'short_description' => 'Stock and picking for a warehouse.',
            'status' => 'archived',
            'project_status' => 'completed',
            'gallery' => [['image' => $image, 'alt' => 'Picking screen', 'remove' => '1']],
        ]);

    expect($project->refresh()->gallery)->toBe([])
        ->and(public_path('uploads/'.$image))->not->toBeFile();
});

it('ignores gallery paths that do not belong to the project', function () {
    $project = CaseStudy::factory()->create();

    $this->actingAs(portfolioAdmin())
        ->put(route('admin.case-studies.update', $project), [
            'title' => $project->title,
            'short_description' => $project->short_description,
            'status' => 'published',
            'project_status' => 'completed',
            'gallery' => [['image' => '../../.env', 'alt' => 'x']],
        ]);

    expect($project->refresh()->gallery)->toBe([]);
});

it('renders every field on the project create and edit forms', function () {
    $project = CaseStudy::factory()->create();
    $fields = ['title', 'client_name', 'status', 'project_status', 'project_date', 'case_study_category_id', 'service_id', 'industry_id', 'location', 'slug', 'sort_order', 'is_featured', 'short_description', 'challenge', 'solution', 'modules', 'technologies', 'features', 'outcome', 'testimonial_id', 'content', 'image', 'image_alt', 'gallery_uploads[]', 'seo_title', 'seo_description', 'theme', 'icon'];
    $admin = portfolioAdmin();

    foreach ([route('admin.case-studies.create'), route('admin.case-studies.edit', $project)] as $url) {
        $response = $this->actingAs($admin)->get($url)->assertOk();

        foreach ($fields as $field) {
            $response->assertSee('name="'.$field.'"', false);
        }
    }
});

it('saves a dragged project order', function () {
    CaseStudy::query()->delete();

    [$first, $second, $third] = CaseStudy::factory()->count(3)->create(['sort_order' => 0]);

    $this->actingAs(portfolioAdmin())
        ->postJson(route('admin.case-studies.reorder'), ['ids' => [$third->id, $first->id, $second->id]])
        ->assertOk()
        ->assertJson(['order' => [$third->id => 1, $first->id => 2, $second->id => 3]]);

    expect(CaseStudy::ordered()->pluck('id')->all())->toBe([$third->id, $first->id, $second->id]);
});

it('keeps projects outside a filtered drag in their positions', function () {
    CaseStudy::query()->delete();

    $projects = collect([1, 2, 3, 4])->map(fn (int $order) => CaseStudy::factory()->create(['sort_order' => $order]));
    [$first, $second, $third, $fourth] = $projects;

    $this->actingAs(portfolioAdmin())
        ->postJson(route('admin.case-studies.reorder'), ['ids' => [$fourth->id, $second->id]])
        ->assertOk();

    expect(CaseStudy::ordered()->pluck('id')->all())->toBe([$first->id, $fourth->id, $third->id, $second->id]);
});

it('rejects an invalid project order', function (array $ids) {
    $this->actingAs(portfolioAdmin())
        ->postJson(route('admin.case-studies.reorder'), ['ids' => $ids])
        ->assertUnprocessable();
})->with([
    'unknown project' => [[999999]],
    'nothing submitted' => [[]],
]);

it('requires sign-in to reorder projects', function () {
    $this->postJson(route('admin.case-studies.reorder'), ['ids' => [1]])->assertUnauthorized();
});

it('offers a drag handle for each project in the admin list', function () {
    $project = CaseStudy::factory()->create();

    $this->actingAs(portfolioAdmin())
        ->get(route('admin.case-studies.index'))
        ->assertOk()
        ->assertSee('data-reorder-id="'.$project->id.'"', false)
        ->assertSee('data-reorder-handle', false);
});

it('filters the admin project list by status', function () {
    CaseStudy::factory()->create(['title' => 'Live One']);
    CaseStudy::factory()->draft()->create(['title' => 'Draft One']);

    $this->actingAs(portfolioAdmin())
        ->get(route('admin.case-studies.index', ['status' => 'draft']))
        ->assertOk()
        ->assertSee('Draft One')
        ->assertDontSee('Live One');
});

it('maps older category names onto service categories', function (string $legacy, string $expected) {
    expect(PortfolioCategories::match($legacy))->toBe($expected);
})->with([
    ['Seamless E-Commerce Solution', 'E-commerce'],
    ['Modern Website Redesign', 'Website'],
    ['A Digital Success Story', 'Website'],
    ['ERP SOLUTION', 'Software'],
    ['AI Chatbot', 'AI / Automation'],
    ['Retail', 'Other'],
]);

it('publishes the hosted client and product projects with their cover and screenshots', function () {
    $projects = CaseStudy::whereIn('slug', ['tenderset-government-tender-platform', 'alignocare-dental-clinic-website', 'society-management-saas-platform'])->get();

    expect($projects)->toHaveCount(3)
        ->and($projects->every(fn (CaseStudy $project): bool => $project->isPublished()))->toBeTrue();

    foreach (CaseStudy::whereNotNull('gallery')->get() as $project) {
        expect(public_path('uploads/'.$project->image))->toBeFile();

        foreach ($project->galleryImages() as $shot) {
            expect(public_path('uploads/'.$shot['image']))->toBeFile();
        }
    }

    $this->get(route('case-studies.show', 'tenderset-government-tender-platform'))
        ->assertOk()
        ->assertSee('TenderSet Government Tender Platform')
        ->assertSee('Tectignis (own product)')
        ->assertSee('Saved searches with email and WhatsApp alerts');
});
