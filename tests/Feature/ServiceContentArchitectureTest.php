<?php

use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('casts the content column to an array and persists structured sections', function () {
    $service = Service::factory()->create([
        'content' => [
            'hero' => ['heading' => 'Custom Software', 'bullets' => ['Fast', 'Secure']],
            'faq' => ['enabled' => true, 'items' => [['question' => 'Q?', 'answer' => 'A.']]],
        ],
    ]);

    $service->refresh();

    expect($service->content)->toBeArray()
        ->and($service->content['hero']['heading'])->toBe('Custom Software')
        ->and($service->content['hero']['bullets'])->toBe(['Fast', 'Secure'])
        ->and($service->content['faq']['enabled'])->toBeTrue();
});

it('attaches tech stacks and industries through the pivots', function () {
    $service = Service::factory()->create();
    $tech = TechStack::factory()->count(2)->create();
    $industries = Industry::factory()->count(3)->create();

    $service->techStacks()->sync($tech->pluck('id'));
    $service->industries()->sync($industries->pluck('id'));

    expect($service->techStacks)->toHaveCount(2)
        ->and($service->industries)->toHaveCount(3)
        ->and($tech->first()->services)->toHaveCount(1)
        ->and($industries->first()->services)->toHaveCount(1);
});

it('loads services.show for a service with content and attached relations', function () {
    $service = Service::factory()->create([
        'is_active' => true,
        'slug' => 'rich-service',
        'content' => [
            'hero' => ['heading' => 'Rich Service', 'intro' => 'Intro copy.'],
        ],
    ]);

    $service->techStacks()->sync(TechStack::factory()->count(2)->create()->pluck('id'));
    $service->industries()->sync(Industry::factory()->count(2)->create()->pluck('id'));

    $this->get(route('services.show', $service->slug))->assertOk();

    expect($service->fresh()->load('techStacks', 'industries')->techStacks)->toHaveCount(2);
});

it('accepts the three reconciled category values', function () {
    foreach (Service::CATEGORIES as $category) {
        $service = Service::factory()->create(['category' => $category]);

        expect($service->fresh()->category)->toBe($category);
    }
});
