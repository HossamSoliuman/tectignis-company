<?php

use App\Enums\UserRole;
use App\Models\CaseStudy;
use App\Models\LandingPage;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Translation;
use App\Models\User;
use Database\Seeders\LandingPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // The deploy migration seeds the two priority pages; start each test empty.
    LandingPage::query()->delete();
});

function landingService(): Service
{
    return Service::factory()->create([
        'slug' => 'custom-software-development',
        'content' => [
            'benefits' => ['enabled' => true, 'heading' => 'Business Benefits of Custom Software', 'items' => [['title' => 'Lower Operating Costs', 'description' => 'Less manual work.']]],
            'process' => ['enabled' => true, 'steps' => [['title' => 'Discovery & Planning', 'description' => 'Understand your needs.']]],
            'faq' => ['enabled' => true, 'items' => [['question' => 'Service FAQ question?', 'answer' => 'Service FAQ answer.']]],
        ],
    ]);
}

it('renders a landing page with every section and the preset enquiry form', function () {
    $caseStudy = CaseStudy::factory()->create(['title' => 'Logistics Portal']);
    $service = landingService();
    $service->update(['content' => [...$service->content, 'case_studies' => ['enabled' => true, 'ids' => [$caseStudy->id]]]]);

    $page = LandingPage::factory()->for($service)->create([
        'slug' => 'custom-software',
        'enquiry_service' => 'Custom Software',
        'content' => [
            'bullets' => ['In-house team'],
            'faq' => ['items' => [['question' => 'Do you work with clients outside India?', 'answer' => 'Yes.']]],
        ],
    ]);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSeeInOrder([
            'Custom Software Development for Growing Businesses',
            'Discuss Your Project',
            'Request a Consultation',
            'Business Benefits of Custom Software',
            'Project Examples',
            'Logistics Portal',
            'How We Deliver Your Project',
            'Discovery &amp; Planning',
            'Do you work with clients outside India?',
        ], false)
        ->assertSee('In-house team')
        ->assertSee('name="landing_page" value="custom-software"', false)
        ->assertSee('<option value="Custom Software" selected>', false)
        ->assertSee(route('legal.show', 'privacy-policy'))
        ->assertDontSee('Service FAQ question?');
});

it('keeps navigation minimal', function () {
    $page = LandingPage::factory()->create();

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertDontSee(route('about'))
        ->assertDontSee(route('blog.index'))
        ->assertDontSee('id="consultation-modal"', false);
});

it('falls back to the linked service FAQs when the page has none', function () {
    $page = LandingPage::factory()->for(landingService())->create();

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('Service FAQ question?');
});

it('returns 404 for inactive or unknown landing pages', function () {
    $page = LandingPage::factory()->inactive()->create();

    $this->get(route('landing-pages.show', $page->slug))->assertNotFound();
    $this->get(route('landing-pages.show', 'missing'))->assertNotFound();
});

it('tags a lead with the landing page it was submitted from', function () {
    $page = LandingPage::factory()->create(['slug' => 'website-development', 'enquiry_service' => 'Website Development']);

    $this->post(route('contact.submit'), validEnquiry(['service' => 'Website Development', 'landing_page' => $page->slug]))
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole())
        ->landing_page->toBe('website-development')
        ->service->toBe('Website Development')
        ->and(Lead::sole()->landingPage->is($page))->toBeTrue();
});

it('drops an unknown landing page instead of rejecting the lead', function () {
    $this->post(route('contact.submit'), validEnquiry(['landing_page' => 'tampered']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole()->landing_page)->toBeNull();
});

it('files the secondary CTA submission as a consultation request', function () {
    $page = LandingPage::factory()->create();

    $this->post(route('contact.submit'), validEnquiry(['source' => 'consultation', 'landing_page' => $page->slug]))
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole())->source->toBe('consultation')->landing_page->toBe($page->slug);
});

it('keeps landing pages out of search results and the sitemap', function () {
    enableLanguages('ar');
    $page = LandingPage::factory()->create(['slug' => 'active-page']);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false);

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertDontSee('/lp/active-page');
});

it('asks landing page visitors for a phone number instead of the company and project description', function () {
    $page = LandingPage::factory()->create(['slug' => 'website-development']);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('class="phone-field__input" required="required" name="phone"', false)
        ->assertDontSee('minlength="20"', false);

    $this->post(route('contact.submit'), validEnquiry(['landing_page' => $page->slug, 'phone' => '']))
        ->assertSessionHasErrors('phone');

    $this->post(route('contact.submit'), validEnquiry(['landing_page' => $page->slug, 'company' => '', 'message' => '']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole())->company->toBeNull()->message->toBe('');
});

it('still requires the company and project description on the other enquiry forms', function () {
    $this->post(route('contact.submit'), validEnquiry(['phone' => '', 'company' => '', 'message' => '']))
        ->assertSessionHasErrors(['company', 'message'])
        ->assertSessionDoesntHaveErrors('phone');
});

it('shows only the projects picked for the linked service', function () {
    CaseStudy::factory()->create(['title' => 'Unrelated Website']);
    $page = LandingPage::factory()->for(landingService())->create();

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertDontSee('Project Examples')
        ->assertDontSee('Unrelated Website');
});

it('picks the website projects for the website service and capitalises their title on deploy', function () {
    $shree = CaseStudy::factory()->create(['slug' => 'shree-vakratunda-saree']);
    $mangosteen = CaseStudy::factory()->create(['slug' => 'mangosteen-homestays']);
    $packaging = CaseStudy::factory()->create(['slug' => 'perfect-packaging-solution', 'title' => 'Perfect packaging solution']);
    $translation = Translation::factory()->of($packaging, 'title', 'حل التغليف المثالي')->create();
    $website = Service::factory()->create(['slug' => 'website-design-development', 'content' => ['case_studies' => ['enabled' => true]]]);
    $software = Service::factory()->create(['slug' => 'custom-software-development', 'content' => ['case_studies' => ['enabled' => true]]]);

    (require database_path('migrations/2026_10_19_142736_pick_landing_page_project_examples.php'))->up();

    expect($website->fresh()->content['case_studies']['ids'])->toBe([$shree->id, $mangosteen->id, $packaging->id])
        ->and($software->fresh()->content['case_studies'])->not->toHaveKey('ids')
        ->and($packaging->fresh()->title)->toBe('Perfect Packaging Solution')
        ->and($translation->fresh()->source_hash)->toBe(Translation::hash('Perfect Packaging Solution'));
});

it('keeps the project picks an admin made when the deploy migration runs', function () {
    $picked = CaseStudy::factory()->create();
    CaseStudy::factory()->create(['slug' => 'shree-vakratunda-saree']);
    $website = Service::factory()->create(['slug' => 'website-design-development', 'content' => ['case_studies' => ['ids' => [(string) $picked->id]]]]);

    (require database_path('migrations/2026_10_19_142736_pick_landing_page_project_examples.php'))->up();

    expect($website->fresh()->content['case_studies']['ids'])->toBe([(string) $picked->id]);
});

it('shows the website projects on the custom software page until software projects are picked', function () {
    $shree = CaseStudy::factory()->create(['slug' => 'shree-vakratunda-saree', 'title' => 'Shree Vakratunda Saree Collection']);
    $mangosteen = CaseStudy::factory()->create(['slug' => 'mangosteen-homestays']);
    $packaging = CaseStudy::factory()->create(['slug' => 'perfect-packaging-solution']);
    $service = landingService();
    $page = LandingPage::factory()->for($service)->create();

    (require database_path('migrations/2026_10_19_173459_pick_interim_projects_for_custom_software.php'))->up();

    expect($service->fresh()->content['case_studies']['ids'])->toBe([$shree->id, $mangosteen->id, $packaging->id]);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('Project Examples')
        ->assertSee('Shree Vakratunda Saree Collection');
});

it('keeps the software projects an admin picked when the interim migration runs', function () {
    $picked = CaseStudy::factory()->create();
    CaseStudy::factory()->create(['slug' => 'shree-vakratunda-saree']);
    $service = Service::factory()->create(['slug' => 'custom-software-development', 'content' => ['case_studies' => ['ids' => [(string) $picked->id]]]]);

    (require database_path('migrations/2026_10_19_173459_pick_interim_projects_for_custom_software.php'))->up();

    expect($service->fresh()->content['case_studies']['ids'])->toBe([(string) $picked->id]);
});

it('closes with its own heading instead of repeating the headline', function () {
    $page = LandingPage::factory()->create(['headline' => 'Websites That Win Work']);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('<h2 class="lp-cta__title">Ready to Start Your Project?</h2>', false);

    $page->update(['content' => ['cta' => ['heading' => 'Let us build your website']]]);

    $this->get(route('landing-pages.show', $page->slug))
        ->assertOk()
        ->assertSee('<h2 class="lp-cta__title">Let us build your website</h2>', false);
});

it('seeds both priority landing pages linked to their services', function () {
    Service::factory()->create(['slug' => 'custom-software-development']);
    Service::factory()->create(['slug' => 'website-design-development']);

    (new LandingPageSeeder)->run();
    (new LandingPageSeeder)->run();

    expect(LandingPage::count())->toBe(2);

    $software = LandingPage::where('slug', 'custom-software-development')->sole();
    $website = LandingPage::where('slug', 'website-development')->sole();

    expect($software)
        ->headline->toBe('Custom Software Development for Growing Businesses')
        ->primary_cta_label->toBe('Discuss Your Project')
        ->secondary_cta_label->toBe('Request a Consultation')
        ->enquiry_service->toBe('Custom Software')
        ->and($software->service->slug)->toBe('custom-software-development')
        ->and($website)
        ->headline->toBe('Professional Website Development for Your Business')
        ->primary_cta_label->toBe('Request a Website Quote')
        ->secondary_cta_label->toBe('Discuss Your Requirements')
        ->enquiry_service->toBe('Website Development')
        ->and($website->service->slug)->toBe('website-design-development');
});

it('lets admins create, update and delete landing pages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $this->actingAs($admin)->get(route('admin.landing-pages.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.landing-pages.create'))->assertOk();

    $this->actingAs($admin)->post(route('admin.landing-pages.store'), [
        'title' => 'Website Development',
        'slug' => 'website-development',
        'service_id' => $service->id,
        'enquiry_service' => 'Website Development',
        'headline' => 'Professional Website Development for Your Business',
        'primary_cta_label' => 'Request a Website Quote',
        'secondary_cta_label' => 'Discuss Your Requirements',
        'content' => [
            'bullets' => ['Responsive', ''],
            'faq' => ['items' => [['question' => 'How long?', 'answer' => '2 weeks'], ['question' => '', 'answer' => '']]],
        ],
        'is_active' => '1',
    ])->assertRedirect();

    $page = LandingPage::sole();

    expect($page->content['bullets'])->toBe(['Responsive'])
        ->and($page->content['faq']['items'])->toHaveCount(1);

    $this->actingAs($admin)->get(route('admin.landing-pages.edit', $page))->assertOk()->assertSee('Responsive');

    $this->actingAs($admin)->put(route('admin.landing-pages.update', $page), [
        'title' => 'Website Development',
        'slug' => 'website-development',
        'enquiry_service' => 'Website Development',
        'headline' => 'New Headline',
        'primary_cta_label' => 'Request a Website Quote',
        'secondary_cta_label' => 'Discuss Your Requirements',
        'is_active' => '0',
    ])->assertRedirect(route('admin.landing-pages.edit', $page));

    expect($page->fresh())->headline->toBe('New Headline')->is_active->toBeFalse();

    $this->actingAs($admin)->delete(route('admin.landing-pages.destroy', $page))->assertRedirect(route('admin.landing-pages.index'));

    expect(LandingPage::count())->toBe(0);
});

it('rejects a form service outside the approved list', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->post(route('admin.landing-pages.store'), [
            'title' => 'Bad',
            'slug' => 'bad',
            'enquiry_service' => 'Time travel',
            'headline' => 'Headline',
            'primary_cta_label' => 'Go',
            'secondary_cta_label' => 'Go',
        ])
        ->assertSessionHasErrors('enquiry_service');

    expect(LandingPage::count())->toBe(0);
});

it('filters leads by landing page and shows it on the lead', function () {
    $page = LandingPage::factory()->create(['slug' => 'website-development', 'title' => 'Website Development']);
    $tagged = Lead::factory()->enquiry()->create(['name' => 'Tagged Lead', 'landing_page' => $page->slug]);
    Lead::factory()->enquiry()->create(['name' => 'Other Lead']);
    $admin = User::factory()->role(UserRole::SuperAdmin)->create();

    $this->actingAs($admin)->get(route('admin.leads.index', ['landing_page' => $page->slug]))
        ->assertOk()
        ->assertSee('Tagged Lead')
        ->assertDontSee('Other Lead');

    $this->actingAs($admin)->get(route('admin.leads.show', $tagged))
        ->assertOk()
        ->assertSee('Ads landing page')
        ->assertSee('Website Development');
});
