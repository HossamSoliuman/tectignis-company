<?php

use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function fullyPopulatedService(array $overrides = []): Service
{
    $service = Service::factory()->create(array_merge([
        'is_active' => true,
        'slug' => 'rich-service',
        'title' => 'Custom Software Development',
        'body' => null,
        'category' => 'software_development',
        'content' => [
            'hero' => ['heading' => 'Rich Hero Heading', 'intro' => 'Hero intro copy.', 'bullets' => ['Hero Bullet One']],
            'features_strip' => ['enabled' => true, 'items' => [['icon' => null, 'label' => 'On-Time Delivery']]],
            'sub_services' => ['enabled' => true, 'heading' => 'What We Offer', 'items' => [['title' => 'Sub Service Card', 'description' => 'Desc.']]],
            'process' => ['enabled' => true, 'heading' => 'Our Process', 'steps' => [['title' => 'Process Step One', 'description' => 'Step desc.']]],
            'tech' => ['enabled' => true, 'heading' => 'Technologies'],
            'industries' => ['enabled' => true, 'heading' => 'Industries'],
            'case_studies' => ['enabled' => true, 'heading' => 'Success Stories'],
            'why_choose' => ['enabled' => true, 'heading' => 'Why Choose Us', 'points' => ['Why Choose Point One'], 'cta_label' => 'Talk to us'],
            'faq' => ['enabled' => true, 'items' => [['question' => 'A Frequent Question?', 'answer' => 'An answer.']]],
            'cta_band' => ['enabled' => true, 'heading' => 'Ready To Start Band'],
        ],
    ], $overrides));

    $service->techStacks()->sync(TechStack::factory()->create(['name' => 'AttachedTech', 'is_active' => true])->id);
    $service->industries()->sync(Industry::factory()->create(['name' => 'AttachedIndustry', 'is_active' => true])->id);
    CaseStudy::factory()->create(['title' => 'Recent Case Study', 'is_active' => true]);

    return $service;
}

it('renders every section for a fully populated service', function () {
    $service = fullyPopulatedService();

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee('Rich Hero Heading')
        ->assertSee('On-Time Delivery')
        ->assertSee('What We Offer')
        ->assertSee('Sub Service Card')
        ->assertSee('Our Process')
        ->assertSee('Process Step One')
        ->assertSee('AttachedTech')
        ->assertSee('AttachedIndustry')
        ->assertSee('Recent Case Study')
        ->assertSee('Why Choose Point One')
        ->assertSee('A Frequent Question?')
        ->assertSee('Ready To Start Band');
});

it('hides a section whose enabled flag is false', function () {
    $service = fullyPopulatedService();
    $content = $service->content;
    $content['faq']['enabled'] = false;
    $content['sub_services']['enabled'] = false;
    $service->update(['content' => $content]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertDontSee('A Frequent Question?')
        ->assertDontSee('Sub Service Card')
        ->assertSee('Rich Hero Heading');
});
